<?php

namespace App\Interfaces\Services;

interface IIdUffsService
{
    function authWithIdUFFS(string $uid, string $password);

    function isActive(string $enrollment_id): bool;

    function validateAtIdUffs($uid, $password);
}
