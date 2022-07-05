<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApiResponse;
use App\Traits\UserTypeTrait;
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

    public function createUserWithIdUFFS(Request $request)
    {
        try {
            $user = [
                "uid" => $request->uid,
                "password" => $request->password,
                "type" => ($request->type != 0) ? $request->type : null,
                "profile_photo" => $request->profile_photo,
                "enrollment_id" => $request->enrollment_id
            ];

            $validation = Validator::make($user, $this->createUserWithIdUFFSRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->createUserWithIdUFFS($user);

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
            $user = [];

            if ($request->type and $request->type != 0) {
                $user["type"] = $request->type;
            }

            if ($request->enrollment_id) {
                $user["enrollment_id"] = $request->enrollment_id;
            }

            if ($request->profile_photo) {
                $user["profile_photo"] = $request->profile_photo;
            }

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
            $user = [];
            if ($request->email) {
                $user["email"] = $request->email;
            }

            if ($request->name) {
                $user["name"] = $request->name;
            }

            if ($request->type and $request->type != 0) {
                $user["type"] = $request->type;
            }

            if ($request->profile_photo) {
                $user["profile_photo"] = $request->profile_photo;
            }

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

    private function createUserWithIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string'],
            'type' => [Rule::in(config('user.users_auth_iduffs')),'required'],
            'profile_photo' => ['required', 'string'],
            'enrollment_id' => ['required', 'string', 'max:10', 'min:10',  'unique:users']
        ];
    }

    private function createUserWitoutIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'type' => [Rule::in(config('user.users_auth_locally')),'required'],
            'profile_photo' => ['required', 'string'],
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
        return  [
            'type' => [Rule::in(config('user.users_auth_iduffs'))],
            'profile_photo' => ['string'],
            'enrollment_id' => [Rule::unique('users')->ignore($enrollment_id, 'enrollment_id'), 'string', 'max:10', 'min:10']
        ];
    }

    private function updateUserWithoutIdUFFSRules($email): array
    {
        return  [
            'email' => [Rule::unique('users')->ignore($email, 'email'), 'email'],
            'name' => ['string', 'max:255'],
            'type' => [Rule::in(config('user.users_auth_locally'))],
            'profile_photo' => ['string'],
        ];
    }

}
