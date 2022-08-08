<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IMenuRepository;
use App\Models\Menu;

class MenuRepository implements IMenuRepository
{
    public function createOrUpdate($data)
    {
        $date = $data["date"];
        unset($data["date"]);

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

    public function getMenuById($id){
        return Menu::where('id', $id)->first();
    }

    public function getMenuByDate($date){
        return Menu::where('date', $date)->first();
    }
}
