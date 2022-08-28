<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IRoomService;
use App\Models\Api\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    private IRoomService $service;

    public function __construct(IRoomService $service)
    {
        $this->service = $service;
    }

    public function createRoom(Request $request){
        try {
            $room = [
                "name" => $request->name,
                "description" => $request->description,
                "capacity" => $request->capacity,
                "status_room" => $request->status_room,
                "responsable_id" => $request->responsable_id,
                "block_id" => $request->block_id
            ];

            $validation = Validator::make($room, $this->createRoomRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createRoom($room);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateRoom(Request $request, $id){
        try {
            $room = [
                "name" => $request->name,
                "description" => $request->description,
                "capacity" => $request->capacity,
                "status_room" => $request->status_room,
                "responsable_id" => $request->responsable_id,
            ];

            $room = array_filter($room);

            $validation = Validator::make($room, $this->updateRoomRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->updateRoom($room, $id);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function deleteRoom($id){
        try {
            //$room = $this->service->deleteRoom($room);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getRoom(){
        try {
            $rooms = $this->service->getRoom();

            return ApiResponse::ok($rooms);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function updateRoomRules()
    {
        return [
            "name" => ['string'],
            "description" => ['string']
        ];
    }

    private function createRoomRules()
    {
        return [
            "name" => ['required', 'string'],
            "description" => ['string']
        ];
    }

}
