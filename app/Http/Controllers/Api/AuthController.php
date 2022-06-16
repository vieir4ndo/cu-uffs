<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\UserService;
use App\Models\Api\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    private UserService $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function login(Request $request)
    {
        $credentials = [
            "uid" => $request->uid,
            "passwrod" => $request->password
        ];

        $user = $this->service->getUserByUsername($request->uid);

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->uid);

            return ApiResponse::ok(['token' => $token->plainTextToken]);
        } else {
            return ApiResponse::badRequest("A senha informada está incorreta.");
        }
    }
}
