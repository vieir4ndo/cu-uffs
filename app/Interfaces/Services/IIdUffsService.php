<?php

namespace App\Interfaces\Services;

interface IIdUffsService
{
    function authWithIdUFFS($uid, $password);
    function isActive($enrollment_id): bool;
}
