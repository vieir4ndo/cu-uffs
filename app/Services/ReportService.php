<?php

namespace App\Services;

use App\Interfaces\Services\IEntryService;
use App\Interfaces\Services\IReportService;
use App\Interfaces\Services\ITicketService;

class ReportService implements IReportService
{
    private IEntryService $entryService;
    private ITicketService $ticketService;

    public function __construct(
        IEntryService  $entryService,
        ITicketService $ticketService,
    )
    {
        $this->entryService = $entryService;
        $this->ticketService = $ticketService;
    }

    public function generateEntryReport($init_date, $final_date){
        return $this->entryService->generateReport($init_date, $final_date);
    }

    public function generateTicketReport($init_date, $final_date){
        return $this->ticketService->generateReport($init_date, $final_date);
    }

}
