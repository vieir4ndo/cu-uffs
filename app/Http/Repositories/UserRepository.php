<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function createUser($user){
        return User::create($user);
    }

    public function getUserByUsername(string $username) : \App\Models\User
    {
        return User::where("uid", $username)->first();
    }

}
