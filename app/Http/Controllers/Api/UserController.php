<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;

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
            /*Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
            ])->validate();*/

            $user = [
                "uid" => $request->uid,
                "email" => $request->email,
                "name" => $request->name,
                "password" => $request->password,
                "type" => $request->type,
                "profile_photo" => $request->profile_photo,
                "enrollment_id" => $request->enrollment_id
            ];

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

    public function updateProfilePicture(Request $request, string  $uid)
    {
        try {
            /*Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
            ])->validate();*/

            $user = [
                "profile_photo" => $request->profile_photo,
            ];

            $savedUser = $this->service->updateUser($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateUser(Request $request, string $uid)
    {
        try {
            /*Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
            ])->validate();*/

            $user = [
                "email" => $request->email,
                "name" => $request->name,
                "type" => $request->type,
            ];

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

}
