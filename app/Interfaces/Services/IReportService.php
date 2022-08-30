<?php

namespace App\Interfaces\Services;

interface IReportService
{
    function generateEntryReport($init_date, $final_date);
    function generateTicketReport($init_date, $final_date);
}
