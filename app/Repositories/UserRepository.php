<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function createOrUpdate($data)
    {
        if ($data["type"] == null) {
            $user = $this->getUserByUsername($data["uid"]);
        }

        return User::updateOrCreate(
            ["uid" => $data["uid"]],
            [
                "password" => $data["password"] ?? $user->email,
                "profile_photo" => $data["profile_photo"] ?? $user->email,
                "enrollment_id" => $data["enrollment_id"] ?? $user->email,
                "birth_date" => $data["birth_date"] ?? $user->email,
                "course" => $data["course"] ?? $user->email,
                "bar_code" => $data["bar_code"] ?? $user->email,
                "status_enrollment_id" => $data["status_enrollment_id"] ?? $user->email,
                "type" => $data["type"] ??  $user->type,
                "name" => $data["name"] ?? $user->name,
                "email" => $data["email"] ?? $user->email,
            ]
        );
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
