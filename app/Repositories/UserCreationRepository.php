<?php

namespace App\Repositories;

use App\Models\UserCreation;

class UserCreationRepository
{
    public function create($data)
    {
        return UserCreation::create($data);
    }

    public function getByUid(string $uid)
    {
        return UserCreation::where("uid", $uid)->first();
    }

    public function update($uid, $data)
    {
        return UserCreation::where('uid', $uid)->update($data);
    }
}
