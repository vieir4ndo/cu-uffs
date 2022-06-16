<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController
{
    private UserService $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function createUser(Request $request)
    {
        try {
            $user = [
                "uid" => $request->uid,
                "email" => $request->email,
                "name" => $request->name,
                "password" => $request->password,
                "type" => $request->type,
                "profile_photo" => $request->profile_photo,
                "enrollment_id" => $request->enrollment_id
            ];

            $validation = Validator::make($user, $this->createUserRules());

            if ($validation->fails() ) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->createUser($user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getUser($uid)
    {
        try {
            $user = $this->service->getUserByUsername($uid);

            return ApiResponse::ok($user);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateProfilePicture(Request $request, string $uid)
    {
        try {
            $user = [
                "profile_photo" => $request->profile_photo,
            ];

            $validation = Validator::make($user, $this->updateProfilePictureRules());

            if ($validation->fails() ) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->updateUser($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateUser(Request $request, string $uid)
    {
        try {
            $user = [
                "email" => $request->email,
                "name" => $request->name,
                "type" => $request->type,
            ];

            $validation = Validator::make($user, $this->updateUserRules($uid));

            if ($validation->fails() ) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->updateUser($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function deleteUser($uid)
    {
        try {
            $result = $this->service->deleteUserByUsername($uid);

            return ApiResponse::ok(null, $result);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function createUserRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'email' => ['required','email',  'unique:users'],
            'password' => [
                'required',
                'string',
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'int'],
            'profile_photo' => ['required', 'string'],
            'enrollment_id' => ['required', 'string', 'max:10', 'min:10',  'unique:users']
        ];
    }

    private function updateProfilePictureRules()
    {
        return [
            'profile_photo' => ['required', 'string'],
        ];
    }

    private function updateUserRules($uid)
    {
        return [
            'email' => ['required','email',
                Rule::unique('users')->ignore($uid, 'uid')],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'int'],
        ];
    }

}
