<?php

namespace App\Enums;

enum UserOperationStatus: int
{
    case Solicitaded = 1;
    case Starting = 2;
    case ValidatingIdUFFSCredentials = 3;
    case ValidatingEnrollmentId = 4;
    case ValidatingProfilePhoto = 5;
    case GeneratingBarCode = 6;
    case Suceed = 7;
    case Failed = 8;
}

