<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Services\UserService;

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function createUser(Request $request)
    {
        try {
            $user = new User();
            $user->uid = $request->uid;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = $request->password;
            $user->type = $request->type;

            $savedUser = $this->service->createUser($user);

            return ApiResponse::ok($savedUser);
        }
        catch (Exception $e)
        {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

}
