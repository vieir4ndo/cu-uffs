<?php
namespace App\Interfaces\Services;

use App\Models\User;

interface IUserService
{
    function createUser($user);

    function getUserByUsername(string $uid, $withFiles = true): User;

    function deleteUserByUsername(string $uid): bool;

    function updateUser(string $uid, $data): User;

    function deactivateUser(string $uid, $data): User;

}
