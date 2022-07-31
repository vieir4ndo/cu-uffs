<?php

namespace App\Interfaces\Services;

interface IMenuService
{
    public function createMenu($data);
    public function updateMenu($data, $date);
    public function deleteMenu($date);
    public function getMenu();
}
