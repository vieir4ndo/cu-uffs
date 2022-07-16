<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function createOrUpdate($user)
    {
        $uid = $user["uid"];
        unset($user["uid"]);

        return User::updateOrCreate(["uid" => $uid], $user);
    }

    public function getUserByUsername(string $uid)
    {
        return User::where("uid", $uid)->first();
    }

    public function deleteUserByUsername(string $uid): bool
    {
        return User::where("uid", $uid)->delete();
    }

    public function updateUserByUsername(string $uid, $data): User
    {
        User::where("uid", $uid)->update($data);

        return $this->getUserByUsername($uid);
    }
}
