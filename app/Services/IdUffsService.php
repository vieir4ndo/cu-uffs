<?php

namespace App\Services;

use App\Interfaces\Services\IIdUffsService;
use CCUFFS\Auth\AuthIdUFFS;
use Illuminate\Support\Facades\Hash;

class IdUffsService implements IIdUffsService
{
    public function authWithIdUFFS($uid, $password)
    {
        $credentials = [
            'user' => $uid,
            'password' => $password,
        ];

        $auth = new AuthIdUFFS();
        $user_data = $auth->login($credentials);

        if (!$user_data) {
            return null;
        }

        $password = Hash::make($user_data->pessoa_id);

        return [
            'password' => $password
        ];
    }

    public function isActive($enrollment_id): bool
    {
        return false;
    }
}
