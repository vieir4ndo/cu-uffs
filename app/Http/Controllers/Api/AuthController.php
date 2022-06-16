<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\AuthService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class AuthController
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request)
    {
        try {
            $token = $this->service->login($request->uid, $request->password);

            return ApiResponse::ok(["token" => $token]);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }
}
