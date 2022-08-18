<?php

namespace App\Interfaces\Repositories;

interface IRoomRepository
{
    public function createRoom($data);
    public function updateRoom($data, $id);
    public function deleteRoom($id);
    public function getRoom();
}
