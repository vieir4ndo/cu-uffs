<?php

namespace App\Interfaces\Repositories;

interface IMenuRepository
{
    public function createMenu($data);
    public function updateMenu($data, $date);
    public function deleteMenu($date);
    public function getMenu();
}
