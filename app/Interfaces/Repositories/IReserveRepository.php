<?php

namespace App\Interfaces\Repositories;

interface IReserveRepository
{
    public function createOrUpdateReserve($data);
    public function getReserve();
    public function getReserveById($id);
}
