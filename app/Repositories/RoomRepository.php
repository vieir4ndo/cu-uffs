<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IRoomRepository;
use App\Models\Room;

class RoomRepository implements IRoomRepository
{
    public function createOrUpdateRoom($data)
    {
        $id = $data["id"] ?? null;
        unset($data["id"]);

        return Room::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function getRoom()
    {
        return Room::simplePaginate(15);
    }

    public function getRoomById($id){
        return Room::where('id', $id)->first();
    }
}
