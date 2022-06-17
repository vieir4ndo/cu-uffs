<?php
namespace App\Interfaces\Services;

interface IAuthService
{
    function login($uid, $password);

    function authWithIdUFFS($uid, $password);

    function forgotPassword(string $uid): void;

    function resetPassword(string $uid, string $newpassword);
}
