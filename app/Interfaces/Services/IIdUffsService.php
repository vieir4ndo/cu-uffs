<?php

namespace App\Interfaces\Services;

interface IIdUffsService
{
    function authWithIdUFFS(string $uid, string $password);

    function isActive(string $enrollment_id, string $name);

}
