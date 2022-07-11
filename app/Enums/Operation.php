<?php

namespace App\Enums;

enum Operation: int
{
    case UserCreationWithIdUFFS = 1;
    case UserCreationWithoutIdUFFS = 2;
    case UserUpdateWithIdUFFS = 3;
    case UserUpdateWithoutIdUFFS = 4;
}
