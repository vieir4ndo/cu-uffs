<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ICCRRepository;
use App\Models\CCR;

class CCRRepository implements ICCRRepository
{
    public function createOrUpdateCCR($data) {
        $id = $data["id"] ?? null;
        unset($data["id"]);

        return CCR::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function getCCR() {
        return CCR::where('status_ccr', 1)->simplePaginate(15);
    }

    public function getCCRById($id) {
        return CCR::where('id', $id)->first();
    }
}
