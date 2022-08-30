<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IReserveRepository;
use App\Models\Reserve;
use Illuminate\Support\Facades\DB;
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
            'lessee_id', 'reserves.description as description'
        )
            ->leftJoin('ccr', 'reserves.ccr_id', '=', 'ccr.id')
            ->leftJoin('rooms', 'reserves.room_id', '=', 'rooms.id')
            ->leftJoin('users as responsable', 'rooms.responsable_id', '=', 'responsable.id')
            ->where('reserves.id', $id)
            ->first();
    }

    public function getReservesByLesseeId($id) {
        return Reserve::select('reserves.id as id', 'begin', 'status', 'ccr.name as ccr', 'rooms.name as room')
            ->leftJoin('ccr', 'reserves.ccr_id', '=', 'ccr.id')
            ->leftJoin('rooms', 'reserves.room_id', '=', 'rooms.id')
//            ->where('reserves.end', '>=', Carbon::now())
            ->where('lessee_id', $id)
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

    public function getRoomWithoutReserve($begin, $end){
        /*$first = Room::select('rooms.name as room, rooms.description as description')
            ->leftJoin('reserves', 'reserves.room.id', '=', 'rooms.id')
            ->whereNotIn(function($query){
                $query->select('reserves.room_id')->from('reserves');
            },)->get();

        $theRoom = Reserve::select('rooms.name as room, rooms.description as description')
            ->leftJoin('rooms', 'reserves.room.id', '=', 'rooms.id')
            ->where('reserves.begin', '<', $begin)
            ->orWhere('reserves.end',  '<' , $begin)
            ->where('reserves.end' ,'>', $end)->union($first)->get();*/

        /*$theRoom = DB::select('select r.name from reserves join rooms r on reserves.room_id = r.id
        where reserves.begin < '.$begin.'
        and reserves.end < '.$begin.'
        and reserves.end < '.$end.'

        union

        select r.name from rooms r left join reserves on reserves.room_id = r.id
        where r.id not in (select reserves.room_id from reserves) and r.status_room = true');*/
        $theRoom = DB::select('select * from rooms

        where rooms.status_room=true

        and not exists (
             select * from reserves
             where rooms.id=reserves.room_id
             and reserves.status<>2
             and '.$begin.' between reserves.begin and reserves.end
             and '.$end.' between reserves.begin and reserves.end )'
        );

        return $theRoom;
    }
}
