<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\ICcrService;
use App\Models\Api\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CcrController extends Controller
{
    private ICcrService $service;

    public function __construct(ICcrService $service)
    {
        $this->service = $service;
    }

    public function createccr(Request $request){
        try {
            $ccr = [
                "name" => $request->name,
                "status" => $request->status,
            ];

            $validation = Validator::make($ccr, $this->createcCcrRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createCcr($ccr);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateCcr(Request $request, $id){
        try {
            $ccr = [
                "name" => $request->name,
                "status" => $request->status,
            ];

            $ccr = array_filter($ccr);

            $validation = Validator::make($ccr, $this->updateCcrRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->updateCcr($ccr, $id);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getCcr(){
        try {
            $ccrs = $this->service->getCcr();

            return ApiResponse::ok($ccrs);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function updateCcrRules()
    {
        return [
            "name" => ['string'],
            "status" => ['string']
        ];
    }

    private function createccrRules()
    {
        return [
            "name" => ['required', 'string'],
            "status" => ['string']
        ];
    }

}
