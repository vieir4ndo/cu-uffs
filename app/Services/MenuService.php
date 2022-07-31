<?php

namespace App\Services;

use App\Interfaces\Repositories\IMenuRepository;
use App\Interfaces\Services\IMenuService;
use Carbon\Carbon;

class MenuService implements IMenuService
{
    private IMenuRepository $repository;

    public function __construct(IMenuRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createMenu($data)
    {
        return $this->repository->createMenu($data);
    }

    public function updateMenu($data, $date)
    {
        $this->repository->updateMenu($data, Carbon::parse($date));
    }

    public function deleteMenu($date)
    {
        $this->repository->deleteMenu(Carbon::parse($date));
    }

    public function getMenu()
    {
        return $this->repository->getMenu();
    }
}
