<?php
namespace App\Interfaces\Services;

interface IBarcodeService
{
    function generateBase64($code);
}
