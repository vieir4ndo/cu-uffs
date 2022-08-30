<?php

namespace App\Services;

use App\Interfaces\Repositories\ICCRRepository;
use App\Interfaces\Services\ICCRService;
use Carbon\Carbon;

class CCRService implements ICCRService
{
    private ICCRRepository $repository;

    public function __construct(ICCRRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createCCR($data)
    {
        return $this->repository->createOrUpdateCCR($data);
    }

    public function updateCCR($data, $id)
    {
        $this->repository->createOrUpdateCCR($data, Carbon::parse($id));
    }

    public function getCCR()
    {
        return $this->repository->getCCR();
    }

    public function getCCRById($id)
    {
        return $this->repository->getCCRById($id);
    }
}
