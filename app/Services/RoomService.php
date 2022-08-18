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
        return $this->repository->createOrUpdateRoom($data);
    }

    public function updateRoom($data, $id)
    {
        $this->repository->createOrUpdateRoom($data, Carbon::parse($id));
    }

    public function getRoom()
    {
        return $this->repository->getRoom();
    }

    public function getRoomById($id)
    {
        return $this->repository->getRoomById($id);
    }
}
