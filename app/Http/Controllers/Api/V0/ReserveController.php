<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IReserveService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReserveController extends Controller
{
    private IReserveService $service;

    public function __construct(IReserveService $service) {
        $this->service = $service;
    }

    public function createReserve(Request $request) {
        try {
            $reserve = [
                "begin" => $request->begin,
                "end" => $request->end,
                "description" => $request->description,
                "room_id" => $request->room_id,
                "ccr_id" => $request->ccr_id,
                "locator_id" => $request->user()->id,
            ];

            $validation = Validator::make(array_filter($reserve), $this->createReserveRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createReserve($reserve);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function deleteReserve($id) {
        try {
            //$reserve = $this->service->deleteReserve($reserve);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getLocatorReserves(Request $request) {
        try {
            return ApiResponse::ok($this->service->getReservesByLocatorId($request->user()->id));
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getReserveById(Request $request, $id) {
        try {
            $reserve = $this->service->getReserveById($id);

            if ($reserve->locator_id != $request->user()->id) {
                return ApiResponse::forbidden();
            }

            return ApiResponse::ok($reserve);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function createReserveRules() {
        return [
            "begin" => ['required', 'date'],
            "end" => ['required', 'date'],
            "description" => ['string'],
            "room_id" => ['required', 'integer'],
            "ccr_id" => ['integer'],
            "locator_id" => ['required', 'integer'],
        ];
    }

}
