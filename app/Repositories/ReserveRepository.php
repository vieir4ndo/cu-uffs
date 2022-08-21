<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IReserveRepository;
use App\Models\Reserve;

class ReserveRepository implements IReserveRepository
{
    public function createOrUpdateReserve($data)
    {
        $id = $data["id"] ?? null;
        unset($data["id"]);

        return Reserve::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function getReserve()
    {
        return Reserve::simplePaginate(15);
    }

    public function getReserveById($id){
        return Reserve::where('id', $id)->first();
    }
}
