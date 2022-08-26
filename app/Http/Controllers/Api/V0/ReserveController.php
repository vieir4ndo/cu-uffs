<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IReserveService;
use App\Models\Api\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReserveController extends Controller
{
    private IReserveService $service;

    public function __construct(IReserveService $service)
    {
        $this->service = $service;
    }

    public function createReserve(Request $request){
        try {
            $reserve = [
                "begin" => $request->begin,
                "end" => $request->end,
                "description" => $request->description,
                "room_id" => $request->room_id,
                "ccr_id" => $request->ccr_id,
                "locator_id" => 1,
            ];

            $validation = Validator::make($reserve, $this->createReserveRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createReserve($reserve);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateReserve(Request $request, $id){
        try {
            $reserve = [
                "begin" => $request->begin,
                "end" => $request->end,
                "description" => $request->description,
                "status" => $request->status_reserve,
                "observation" => $request->observation,
                "locator_id" => $request->locator_id,
                "room_id" => $request->room_id,
                "ccr_id" => $request->ccr_id,
            ];

            $reserve = array_filter($reserve);

            $validation = Validator::make($reserve, $this->updateReserveRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->updateReserve($reserve, $id);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function deleteReserve($id){
        try {
            //$reserve = $this->service->deleteReserve($reserve);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getReserve(){
        try {
            $reserves = $this->service->getReserve();

            return ApiResponse::ok($reserves);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }


    private function createReserveRules()
    {
        return [
            "begin" => ['required', 'string'],
            "end" => ['required', 'string'],
            "description" => ['string'],
            "room_id" => ['required', 'integer'],
            "ccr_id" => ['integer'],
            "locator_id" => ['required', 'integer'],
        ];
    }

}
