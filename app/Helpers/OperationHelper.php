<?php

namespace App\Helpers;

use App\Enums\Operation;

class OperationHelper
{
    public static function IsUpdateUserOperation($operation): bool
    {
        return in_array($operation, [Operation::UserUpdateWithoutIdUFFS->value, Operation::UserUpdateWithIdUFFS->value]);
    }
}
