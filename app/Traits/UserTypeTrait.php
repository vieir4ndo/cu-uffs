<?php

namespace App\Traits;

use App\Enums\UserType;

trait UserTypeTrait
{

    public static function names(): array
    {
        return array_column(UserType::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(UserType::cases(), 'value');
    }
}
