<?php

namespace App\Interfaces\Services;

interface ICaptchaMonsterService
{
    function breakRecaptcha(string $websiteUrl, string $websiteKey): string;
}
