<?php

namespace App\Interfaces\Services;

interface ITicketService
{
    function insertTicket($enrollment_id, $data);
    function insertTicketForVisitors($data);
    function getTicketsByUsername($uid);
    function getTicketBalance($uid);
}
