<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\AuthService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function forgotPassword(string $uid)
    {
        try {
            $this->service->forgotPassword($uid);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function resetPassword(Request $request, string $uid)
    {
        try {
            $validation = Validator::make(["new_password" => $request->new_password], $this->resetPasswordRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->resetPassword($uid, $request->new_password);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function resetPasswordRules()
    {
        return [
            'new_password' => [
                'required',
                'string',
            ]
        ];
    }
}
