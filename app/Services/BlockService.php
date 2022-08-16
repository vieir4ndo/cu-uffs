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
        return $this->repository->createBlock($data);
    }

    public function updateBlock($data, $id)
    {
        $this->repository->updateBlock($data, Carbon::parse($id));
    }

    public function deleteBlock($id)
    {
        $this->repository->deleteBlock(Carbon::parse($id));
    }

    public function getBlock()
    {
        return $this->repository->getBlock();
    }
}
