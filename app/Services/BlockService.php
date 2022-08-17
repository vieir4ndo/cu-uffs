<?php

namespace App\Services;

use App\Interfaces\Repositories\IBlockRepository;
use App\Interfaces\Services\IBlockService;
use Carbon\Carbon;

class BlockService implements IBlockService
{
    private IBlockRepository $repository;

    public function __construct(IBlockRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createBlock($data)
    {
        return $this->repository->createOrUpdateBlock($data);
    }

    public function updateBlock($data, $id)
    {
        $this->repository->createOrUpdateBlock($data, Carbon::parse($id));
    }

    public function getBlock()
    {
        return $this->repository->getBlock();
    }

    public function getBlockById($id)
    {
        return $this->repository->getBlockById($id);
    }
}
