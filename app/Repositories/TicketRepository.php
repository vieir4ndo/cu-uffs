<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{
    public function insert($data){
        return Ticket::create($data);
    }

    public function getTicketsById($id){
        return Ticket::select('date_time', "amount", )->where("user_id", $id)->get();
    }
}
