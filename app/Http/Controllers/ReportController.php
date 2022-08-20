<?php

namespace App\Http\Controllers;

use App\Http\Validators\ReportValidator;
use App\Interfaces\Services\ITicketService;
use Illuminate\Http\Request;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Services\IEntryService;

class ReportController extends Controller
{
    private IEntryService $entryService;
    private ITicketService $ticketService;

    public function __construct(IEntryService $service, ITicketService $ticketService)
    {
        $this->entryService = $service;
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        return view('restaurant.report.index');
    }

    public function redirectEntryReport(Request $request)
    {
        try {
            $dates = [
                'init_date' => $request->initDate,
                'final_date' => $request->finalDate
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules());

            // if ($validation->fails()) {
            // $errors = $validation->errors()->all(); with errors
            // }

            $report = $this->entryService->generateReport($request->initDate, $request->finalDate);

            return view('restaurant.report.entry', $report);

        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function redirectTicketReport(Request $request)
    {
        try {
            $dates = [
                'init_date' => $request->initDate,
                'final_date' => $request->finalDate
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules());

            // if ($validation->fails()) {
            // $errors = $validation->errors()->all(); with errors
            // }

            $report = $this->ticketService->generateReport($request->initDate, $request->finalDate);

            return view('restaurant.report.ticket', $report);

        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }
}
