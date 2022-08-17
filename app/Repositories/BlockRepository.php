<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IBlockRepository;
use App\Models\Block;

class BlockRepository implements IBlockRepository
{
    public function createOrUpdateBlock($data)
    {
        $id = $data["id"] ?? null;
        unset($data["id"]);

        return Block::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function getBlock()
    {
        return Block::simplePaginate(15);
    }

    public function getBlockById($id){
        return Block::where('id', $id)->first();
    }
}
