<?php

namespace App\Enums;

enum UserType: int
{
    case default = 1;
    case RUEmployee = 2;
    case ThirdPartyEmployee = 3;
    case ThirdPartyCashierEmployee = 4;
}
