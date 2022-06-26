<?php

namespace Tests\Unit\Services;

use App\Services\BarcodeService;
use Tests\TestCase;

class BarcodeServiceTests extends TestCase
{
    private BarcodeService $barcodeService;

    /** @test */
    public function generateBase64_givenACode_shouldReturnAsExpected()
    {
        $this->barcodeService = new BarcodeService();
        $code = "2021101010";

        $result = $this->barcodeService->generateBase64($code);

        $this->assertNotNull($result);
    }

    /** @test */
    public function generateBase64_givenANullCode_shouldReturnAsExpected()
    {
        $this->barcodeService = new BarcodeService();
        $code = null;

        $result = $this->barcodeService->generateBase64($code);

        $this->assertFalse($result);
    }
}
