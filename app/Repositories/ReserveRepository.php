<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IReserveRepository;
use App\Models\Reserve;
use Carbon\Carbon;

class ReserveRepository implements IReserveRepository
{
    public function createOrUpdateReserve($data) {
        $id = $data["id"] ?? null;
        unset($data["id"]);

        return Reserve::updateOrCreate(
            ["id" => $id],
            $data
        );
    }

    public function deleteReserve($id) {
        return Reserve::where('id', $id)->delete();
    }

    public function getReserve() {
        return Reserve::simplePaginate(15);
    }

    public function getReserveById($id) {
        return Reserve::select(
            'reserves.id as id', 'begin', 'end', 'status', 'ccr.name as ccr', 'rooms.name as room',
            'responsable.name as responsable', 'responsable.id as responsable_id', 'reserves.observation as observation',
            'locator_id', 'reserves.description as description'
        )
            ->leftJoin('ccr', 'reserves.ccr_id', '=', 'ccr.id')
            ->leftJoin('rooms', 'reserves.room_id', '=', 'rooms.id')
            ->leftJoin('users as responsable', 'rooms.responsable_id', '=', 'responsable.id')
            ->where('reserves.id', $id)
            ->first();
    }

    public function getReservesByLocatorId($id) {
        return Reserve::select('reserves.id as id', 'begin', 'status', 'ccr.name as ccr', 'rooms.name as room')
            ->leftJoin('ccr', 'reserves.ccr_id', '=', 'ccr.id')
            ->leftJoin('rooms', 'reserves.room_id', '=', 'rooms.id')
//            ->where('reserves.end', '>=', Carbon::now())
            ->where('locator_id', $id)
            ->simplePaginate(15);
    }

    public function getRequestsByResponsableID($id) {
        return Reserve::select('reserves.id as id', 'begin', 'status', 'ccr.name as ccr', 'rooms.name as room')
            ->leftJoin('ccr', 'reserves.ccr_id', '=', 'ccr.id')
            ->leftJoin('rooms', 'reserves.room_id', '=', 'rooms.id')
            ->leftJoin('users as responsable', 'rooms.responsable_id', '=', 'responsable.id')
//            ->where('reserves.end', '>=', Carbon::now())
            ->where('responsable.id', $id)
            ->simplePaginate(15);
    }
}
