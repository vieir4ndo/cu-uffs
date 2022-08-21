<?php

namespace App\Services;

use App\Interfaces\Repositories\ICcrRepository;
use App\Interfaces\Services\ICcrService;
use Carbon\Carbon;

class CcrService implements ICcrService
{
    private ICcrRepository $repository;

    public function __construct(ICcrRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createCcr($data)
    {
        return $this->repository->createOrUpdateCcr($data);
    }

    public function updateCcr($data, $id)
    {
        $this->repository->createOrUpdateCcr($data, Carbon::parse($id));
    }

    public function getCcr()
    {
        return $this->repository->getCcr();
    }

    public function getCcrById($id)
    {
        return $this->repository->getCcrById($id);
    }
}
