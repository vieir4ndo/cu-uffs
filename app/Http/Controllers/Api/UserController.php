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

    public function createUserWithoutIdUFFS(Request $request)
    {
        try {
            $user = [
                "uid" => $request->uid,
                "email" => $request->email,
                "name" => $request->name,
                "password" => $request->password,
                "type" => ($request->type != 0) ? $request->type : null,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date
            ];

            $validation = Validator::make($user, $this->createUserWitoutIdUFFSRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->createUserWithoutIdUFFS($user);

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

    public function updateUserWithIdUFFS(Request $request, string $uid)
    {
        try {
            $user = [
                "type" => ($request->type and $request->type != 0) ? $request->type : null,
                "enrollment_id" => $request->enrollment_id,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date,
            ];

            $user = array_filter($user);

            $validation = Validator::make($user, $this->updateUserWithIdUFFSRules($request->enrollment_id));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->updateUserWithIdUFFS($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateUserWithoutIdUFFS(Request $request, string $uid)
    {
        try {
            $user = [
                "email" => $request->email,
                "name" => $request->name,
                "type" => $request->type,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date,
            ];

            $user = array_filter($user);

            $validation = Validator::make($user, $this->updateUserWithoutIdUFFSRules($request->email));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->updateUserWithoutIdUFFS($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function createUserWitoutIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'type' => [Rule::notIn(config('user.users_auth_iduffs')), 'required'],
            'profile_photo' => ['required', 'string'],
            'birth_date' => ['required', 'date']
        ];
    }

    private function changeUserActivityUserRules()
    {
        return [
            'active' => ['required', 'bool'],
        ];
    }

    private function updateUserWithIdUFFSRules($enrollment_id): array
    {
        return [
            'type' => [Rule::in(config('user.users_auth_iduffs'))],
            'profile_photo' => ['string'],
            'enrollment_id' => [Rule::unique('users')->ignore($enrollment_id, 'enrollment_id'), 'string', 'max:10', 'min:10'],
            'birth_date' => ['date']
        ];
    }

    private function updateUserWithoutIdUFFSRules($email): array
    {
        return [
            'email' => [Rule::unique('users')->ignore($email, 'email'), 'email'],
            'name' => ['string', 'max:255'],
            'type' => [Rule::notIn(config('user.users_auth_iduffs'))],
            'profile_photo' => ['string'],
            'birth_date' => ['date']
        ];
    }

}
