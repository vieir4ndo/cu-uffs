<?php

namespace App\Interfaces\Repositories;

interface IBlockRepository
{
    public function createBlock($data);
    public function updateBlock($data, $id);
    public function deleteBlock($id);
    public function getBlock();
}
