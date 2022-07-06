<?php

namespace App\Interfaces\Services;

interface IAiPassportPhotoService
{
    function validatePhoto($base64Photo) : string;
}
