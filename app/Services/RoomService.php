<?php

namespace App\Services;

use App\Interfaces\Repositories\IRoomRepository;
use App\Interfaces\Services\IRoomService;
use Carbon\Carbon;

class RoomService implements IRoomService
{
    private IRoomRepository $repository;

    public function __construct(IRoomRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createRoom($data)
    {
        return $this->repository->createRoom($data);
    }

    public function updateRoom($data, $id)
    {
        $this->repository->updateRoom($data, Carbon::parse($id));
    }

    public function deleteRoom($id)
    {
        $this->repository->deleteRoom(Carbon::parse($id));
    }

    public function getRoom()
    {
        return $this->repository->getRoom();
    }
}
