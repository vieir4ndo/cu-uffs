<?php

namespace App\Enums;

enum TicketOrEntryType: int
{
    case Employee = 1;
    case Student = 2;
    case ThirdPartyEmployee = 3;
    case Visitor = 4;
}
