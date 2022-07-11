<?php

namespace App\Interfaces\Services;

use App\Models\User;

interface IUserService
{
    function createUserWithoutIdUFFS($user);

    function getUserByUsername(string $uid, $withFiles = true): User;

    function deleteUserByUsername(string $uid): bool;

    function updateUserWithIdUFFS(string $uid, $data): User;

    function updateUserWithoutIdUFFS(string $uid, $data): User;

    function deactivateUser(string $uid, $data): User;

}
