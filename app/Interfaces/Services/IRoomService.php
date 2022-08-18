<?php

namespace App\Interfaces\Services;

interface IRoomService
{
    public function createRoom($data);
    public function updateRoom($data, $id);
    public function deleteRoom($id);
    public function getRoom();
}
