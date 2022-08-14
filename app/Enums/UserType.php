<?php

namespace App\Enums;

enum UserType: int
{
    case Employee = 1;
    case Student = 2;
    case RUEmployee = 3;
    case ThirdPartyCashierEmployee = 4;
}
