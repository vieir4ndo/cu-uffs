<?php

namespace App\Interfaces\Services;

interface IReserveService
{
    public function createReserve($data);
    public function updateReserve($data, $id);
    public function getReserve();
    public function getReserveById($id);
}
