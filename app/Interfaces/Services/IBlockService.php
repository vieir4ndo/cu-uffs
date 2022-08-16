<?php

namespace App\Interfaces\Services;

interface IBlockService
{
    public function createBlock($data);
    public function updateBlock($data, $id);
    public function deleteBlock($id);
    public function getBlock();
}
