<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use App\Services\UserService;
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

            $validation = Validator::make($user, $this->createUserRules($request->type));

            if ($validation->fails()) {
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

    public function changeUserActivity(Request $request, string $uid)
    {
        try {
            $user = [
                "active" => $request->active,
            ];

            $validation = Validator::make($user, $this->changeUserActivityUserRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->deactivateUser($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateUser(Request $request, string $uid)
    {
        try {
            $user = [];
            if ($request->email) {
                $user["email"] = $request->email;
            }

            if ($request->name) {
                $user["name"] = $request->name;
            }

            if ($request->type) {
                $user["type"] = $request->type;
            }

            if ($request->enrollment_id) {
                $user["enrollment_id"] = $request->enrollment_id;
            }

            if ($request->profile_photo) {
                $user["profile_photo"] = $request->profile_photo;
            }

            $validation = Validator::make($user, $this->updateUserRules($uid, $request->enrollment_id));

            if ($validation->fails()) {
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

    private function createUserRules($type)
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'int'],
            'profile_photo' => ['required', 'string'],
            'enrollment_id' => Rule::requiredIf(function() use ($type) {
                return (in_array($type, config("user.users_auth_iduffs"))) ? ['required', 'string', 'max:10', 'min:10',  'unique:users'] : null;
            })
        ];
    }

    private function changeUserActivityUserRules()
    {
        return [
            'active' => ['required', 'bool'],
        ];
    }

    private function updateUserRules($uid, $enrollment_id): array
    {
        return  [
            'email' => ['email',
                Rule::unique('users')->ignore($uid, 'uid')],
            'name' => ['string', 'max:255'],
            'type' => ['int'],
            'profile_photo' => ['string'],
            'enrollment_id' => ['string', 'max:10', 'min:10','unique:users']
        ];
    }

}
