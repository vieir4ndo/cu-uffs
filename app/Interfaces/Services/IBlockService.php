<?php

namespace App\Interfaces\Services;

interface IBlockService
{
    public function createBlock($data);
    public function updateBlock($data, $id);
    public function getBlock();
    public function getBlockById($id);
}
