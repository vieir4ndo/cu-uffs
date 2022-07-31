<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IMenuRepository;
use App\Models\Menu;

class MenuRepository implements IMenuRepository
{
    public function createMenu($data)
    {
        return Menu::create($data);
    }

    public function updateMenu($data, $date)
    {
        return Menu::updateOrCreate(
            ["date" => $date],
            $data
        );
    }

    public function deleteMenu($date)
    {
        return Menu::where('date', $date)->delete();
    }

    public function getMenu()
    {
        return Menu::simplePaginate(15);
    }
}
