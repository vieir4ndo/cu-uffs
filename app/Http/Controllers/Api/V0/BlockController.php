<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IBlockService;
use App\Models\Api\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{
    private IBlockService $service;

    public function __construct(IBlockService $service)
    {
        $this->service = $service;
    }

    public function createBlock(Request $request){
        try {
            $block = [
                "name" => $request->name,
                "description" => $request->description,
                "status_block" => $request->status
            ];

            $validation = Validator::make($block, $this->createBlockRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createBlock($block);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateBlock(Request $request, $id){
        try {
            $block = [
                "name" => $request->name,
                "description" => $request->description,
                "status_block" => $request->status
            ];

            $block = array_filter($block);

            $validation = Validator::make($block, $this->updateBlockRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->updateBlock($block, $id);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getBlock(){
        try {
            $blocks = $this->service->getBlock();

            return ApiResponse::ok($blocks);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function updateBlockRules()
    {
        return [
            "name" => ['string'],
            "description" => ['string'],
            "status" => ['string']
        ];
    }

    private function createBlockRules()
    {
        return [
            "name" => ['required', 'string'],
            "description" => ['string'],
            "status" => ['string']
        ];
    }

}
