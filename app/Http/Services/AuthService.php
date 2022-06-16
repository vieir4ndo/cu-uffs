<?php

namespace App\Http\Services;

use App\Enums\UserType;
use App\Models\User;
use CCUFFS\Auth\AuthIdUFFS;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Util\Exception;

class AuthService
{
    private UserService $service;
    private User $user;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function login($uid, $password)
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if ($this->user->type == UserType::RUEmployee->value or $this->user->type == UserType::default->value) {
            $data = $this->authWithIdUFFS($uid, $password);
            $this->user->update($data);
        } else {
            if (!Hash::check($password, $this->user->password)) {
                throw new Exception("The password is incorrect.");
            }
        }

        $this->user->tokens()->delete();
        return $this->user->createToken($uid)->plainTextToken;
    }

    public function authWithIdUFFS($uid, $password)
    {
        $credentials = [
            'user' => $uid,
            'password' => $password,
        ];

        $auth = new AuthIdUFFS();
        $user_data = $auth->login($credentials);

        if (!$user_data) {
            throw new Exception("The password is incorrect.");
        }

        $password = Hash::make($user_data->pessoa_id);

        return [
            'password' => $password
        ];
    }
}
