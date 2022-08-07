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
        return $this->repository->createOrUpdate($data);
    }

    public function updateMenu($data, $date)
    {
        $data[] = [
            'date' => Carbon::parse($date)
        ];

        $this->repository->createOrUpdate($data);
    }

    public function deleteMenu($date)
    {
        $this->repository->deleteMenu(Carbon::parse($date));
    }

    public function getLatestMenu(){
        return $this->getMenuByDate(now());
    }

    public function getMenu()
    {
        return $this->repository->getMenu();
    }

    public function getMenuByDate($date){
        return $this->repository->getMenuByDate($date);
    }
}
