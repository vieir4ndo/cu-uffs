<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\ICCRService;
use App\Models\Api\ApiResponse;
use Exception;

class CCRController extends Controller
{
    private ICCRService $service;

    public function __construct(ICCRService $service)
    {
        $this->service = $service;
    }

    public function getCCR(){
        try {
            $blocks = $this->service->getCCR();
            return ApiResponse::ok($blocks);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

}
