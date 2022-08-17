<?php

namespace App\Interfaces\Repositories;

interface IBlockRepository
{
    public function createOrUpdateBlock($data);
    public function getBlock();
    public function getBlockById($id);
}
