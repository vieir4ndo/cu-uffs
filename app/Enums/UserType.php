<?php

namespace App\Enums;

enum UserType: int
{
    case default = 0;
    case RUEmployee = 1;
    case ThirdPartyEmployee = 2;
    case ThirdPartyCashierEmployee = 3;
}
