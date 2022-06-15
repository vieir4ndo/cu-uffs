<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function createUser($user){
        return User::create($user);
    }

    public function getUserByUsername(string $uid)
    {
        return User::where("uid", $uid)->first();
    }

    public function deleteUserByUsername(string $uid) : bool {
        return User::where("uid", $uid)->delete();
    }

    public function updateUserByUsername(string $uid, $data) : User{
        User::where("uid", $uid)->update($data);

        return $this->getUserByUsername($uid);
    }

}
