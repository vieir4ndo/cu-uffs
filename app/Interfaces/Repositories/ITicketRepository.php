<?php

namespace App\Interfaces\Repositories;

interface ITicketRepository
{
    function insert($data);
    function getTicketsById($id);
}
