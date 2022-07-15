<?php

namespace App\Interfaces\Repositories;

use App\Models\User;

interface IUserRepository
{
    function createOrUpdate($user);

    function getUserByUsername(string $uid);

    function deleteUserByUsername(string $uid): bool;

    function updateUserByUsername(string $uid, $data): User;
}
