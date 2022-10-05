<?php

namespace App\Http\Controllers\Api\V0;

use App\Exceptions\ValidationException;
use App\Http\Validators\AuthValidator;
use App\Interfaces\Services\IAuthService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    private IAuthService $service;

    public function __construct(IAuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request)
    {
        $validation = Validator::make(["uid" => $request->uid, "password" => $request->password], AuthValidator::loginRules());

        if ($validation->fails()) {
            throw new ValidationException((string)Arr::flatten($validation->errors()->all()));
        }

        $token = $this->service->login($request->uid, $request->password);

        return ApiResponse::ok(["token" => $token]);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $validation = Validator::make(["uid" => $request->uid], AuthValidator::forgotPasswordRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->forgotPassword($request->uid);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validation = Validator::make(["new_password" => $request->new_password], AuthValidator::resetPasswordRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->resetPassword($request->user()->uid, $request->new_password);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }
}
