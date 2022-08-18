<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IRoomRepository;
use App\Models\Room;

class RoomRepository implements IRoomRepository
{
    public function createRoom($data)
    {
        return Room::create($data);
    }

    public function updateRoom($data, $id)
    {
        return Room::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function deleteRoom($id)
    {
        return Room::where('id', $id)->delete();
    }

    public function getRoom()
    {
        return Room::simplePaginate(15);
    }
}
