<?php

namespace App\Interfaces\Repositories;

interface IRoomRepository
{
    public function createOrUpdateRoom($data);
    public function getRoom();
    public function getRoomById($id);
}
