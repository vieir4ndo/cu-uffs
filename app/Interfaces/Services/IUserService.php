<?php

namespace App\Interfaces\Services;

use App\Models\User;

interface IUserService
{
    function createUserWithoutIdUFFS($user);
    function createOrUpdate($user);
    function getUserByUsername(string $uid, $withFiles = true): \App\Models\User;
    function getUserByUsernameFirstOrDefault(string $uid, $withFiles = true): ?\App\Models\User;
    function deleteUserByUsername(string $uid): bool;
    function updateUserWithIdUFFS(string $uid, $data): User;
    function updateUserWithoutIdUFFS(string $uid, $data): User;
    function updateUser(User $user, $data): User;
    function deactivateUser(string $uid, $data): User;
    function getAllUsersWithIdUFFS();
    function changeUserType(string $uid, $data): User;
    function getUserByEnrollmentId(string $enrollment_id, bool $withFiles = true) : User ;
    function updateTicketAmount($uid, $amount);
    function getStudentCard(string $uid);
    function getAllUsers();
    function getAllNonLesseeUsers();
    function changeLesseePermission(string $uid, $data): User;
}
