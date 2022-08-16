<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IBlockRepository;
use App\Models\Block;

class BlockRepository implements IBlockRepository
{
    public function createBlock($data)
    {
        return Block::create($data);
    }

    public function updateBlock($data, $id)
    {
        return Block::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function deleteBlock($date)
    {
        return Block::where('id', $id)->delete();
    }

    public function getBlock()
    {
        return Block::simplePaginate(15);
    }
}
