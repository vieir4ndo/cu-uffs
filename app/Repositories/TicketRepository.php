<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ITicketRepository;
use App\Models\Entry;
use App\Models\Ticket;

class TicketRepository implements ITicketRepository
{
    public function insert($data){
        return Ticket::create($data);
    }

    public function getTicketsById($id){
        return Ticket::select('date_time', "amount", )->where("user_id", $id)->simplePaginate(15);
    }

    public function getTicketsInInterval($init_date, $final_date){
        return Ticket::where("date_time", ">", $init_date)->where("date_time", "<", $final_date)->get();
    }
}
