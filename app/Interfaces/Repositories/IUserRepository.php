<?php

namespace App\Interfaces\Repositories;

use App\Models\User;

interface IUserRepository
{
    function createOrUpdate($user);
    function getUserByUsername(string $uid);
    function getUserByEnrollmentId(string $enrollment_id);
    function deleteUserByUsername(string $uid): bool;
    function updateUserByUsername(string $uid, $data): User;
    function getAllUsersWithIdUFFS();
    function getStudentCard(string $uid);
    function getAllUsers();
}
