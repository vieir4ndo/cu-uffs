<?php

namespace App\Http\Services;
use Milon\Barcode\DNS1D;

class BarcodeService
{
    public function generateBase64($code)
    {
        return DNS1D::getBarcodePNG("$code", 'C39');
    }
}
