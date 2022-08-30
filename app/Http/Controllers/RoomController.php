<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exception;
use App\Interfaces\Services\IRoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class RoomController extends Controller
{
    private IRoomService $service;

    public function __construct(IRoomService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('room.index');
    }

    public function create()
    {
        return view('room.form', [
            'title' => 'Nova Sala'
        ]);
    }

    public function edit($id)
    {
        return view('room.form', [
            'title' => 'Editar Sala',
            'room' => $this->service->getRoomById($id)
        ]);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            $room = [
                "name" => $request->name,
                "description" => $request->description,
                "status_room" => $request->status,
                "capacity" => $request->capacity,
                "responsable_id" => $request->responsable_id,
                "block_id" => $request->block_id
            ];

            if (isset($request->id)) {
                $room['id'] = $request->id;
            }

            $validation = Validator::make($room, $this->createOrUpdateRoomRules());

            if ($validation->fails()) {
                Alert::error('Erro', Arr::flatten($validation->errors()->all()));
                return back();
            }

            $this->service->createRoom($room);

            Alert::success('Sucesso', 'Sala cadastrada com sucesso!');
            return redirect()->route('web.room.index');
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }

    private static function createOrUpdateRoomRules()
    {
        return [
            "id" => ['string'],
            "name" => ['required', 'string'],
            "description" => ['string'],
            "status_room" => ['required', 'boolean'],
            "responsable_id" => ['string'],
            "block_id" => ['string']
        ];
    }
}
