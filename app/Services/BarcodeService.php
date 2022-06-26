<?php

namespace App\Services;
use App\Interfaces\Services\IBarcodeService;
use Milon\Barcode\DNS1D;

class BarcodeService implements IBarcodeService
{
    public function generateBase64($code)
    {
        return DNS1D::getBarcodePNG("$code", 'C39');
    }
}
