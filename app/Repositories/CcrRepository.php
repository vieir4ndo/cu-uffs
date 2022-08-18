<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ICcrRepository;
use App\Models\Ccr;

class CcrRepository implements ICcrRepository
{
    public function createOrUpdateCcr($data)
    {
        $id = $data["id"] ?? null;
        unset($data["id"]);

        return Ccr::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function getCcr()
    {
        return Ccr::simplePaginate(15);
    }

    public function getCcrById($id)
    {
        return Ccr::where('id', $id)->first();
    }
}
