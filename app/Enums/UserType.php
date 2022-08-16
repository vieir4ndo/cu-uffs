<?php

namespace App\Enums;

enum UserType: int
{
    case Employee = 1;
    case Student = 2;
    case RUEmployee = 3;
    case ThirdPartyEmployee = 4;
    //case ThirdPartyCashierEmployee = 4;
    case CSEmployee = 5;
    case CSUser = 6;
    case CSLocator = 7;
    case CSManager = 8;

    //CSManager irá ser como o administrador do cadastro de salas
}
