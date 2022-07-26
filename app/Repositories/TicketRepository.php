<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{
    public function insert($data){
        return Ticket::create($data);
    }
}
