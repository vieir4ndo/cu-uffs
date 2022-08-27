<?php

namespace App\Services;

use App\Interfaces\Repositories\IReserveRepository;
use App\Interfaces\Services\IReserveService;
use Carbon\Carbon;

class ReserveService implements IReserveService
{
    private IReserveRepository $repository;

    public function __construct(IReserveRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createReserve($data)
    {
        return $this->repository->createOrUpdateReserve($data);
    }

    public function updateReserve($data, $id)
    {
        $this->repository->createOrUpdateReserve($data, Carbon::parse($id));
    }

    public function getReserve()
    {
        return $this->repository->getReserve();
    }

    public function getReserveById($id)
    {
        return $this->repository->getReserveById($id);
    }

    public function getReservesByLocatorId($id)
    {
        return $this->repository->getReservesByLocatorId($id);
    }
}
