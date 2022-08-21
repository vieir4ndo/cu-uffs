<?php

namespace App\Http\Controllers;

use App\Http\Validators\ReportValidator;
use App\Interfaces\Services\ITicketService;
use Illuminate\Http\Request;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Services\IEntryService;
use RealRashid\SweetAlert\Facades\Alert;

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
            // Converter data para dd-mm-yyyy e depois formatar para yyyy-mm-dd
            $formatted_init_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->init_date)));
            $formatted_final_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->final_date)));

            $dates = [
                'init_date' => $formatted_init_date,
                'final_date' => $formatted_final_date
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules());

             if ($validation->fails()) {
                $errors = $validation->errors()->all();
             }

            $report = $this->entryService->generateReport($formatted_init_date, $formatted_final_date);

            return view('restaurant.report.entry', [
                'init_date'                   => $report['init_date'],
                'final_date'                  => $report['final_date'],
                'emission_date'               => $report['emission_date'],
                'entries'                     => $report['entries'],
                'partial_total'               => $report['partial_total'],
                'totals'                      => $report['totals'],
                'averages'                    => $report['averages'],
                'averages_by_day_of_the_week' => $report['averages_by_day_of_the_week']
            ]);
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }

    public function redirectTicketReport(Request $request)
    {
        try {
            // Converter data para dd-mm-yyyy e depois formatar para yyyy-mm-dd
            $formatted_init_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->init_date)));
            $formatted_final_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->final_date)));

            $dates = [
                'init_date' => $request->initDate,
                'final_date' => $request->finalDate
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules());

            if ($validation->fails()) {
                $errors = $validation->errors()->all();
            }

            $report = $this->ticketService->generateReport($formatted_init_date, $formatted_final_date);

            return view('restaurant.report.ticket', [
                'init_date'                   => $report['init_date'],
                'final_date'                  => $report['final_date'],
                'emission_date'               => $report['emission_date'],
                'tickets'                     => $report['tickets'],
                'partial_total'               => $report['partial_total'],
                'totals'                      => $report['totals'],
                'averages'                    => $report['averages'],
                'averages_by_day_of_the_week' => $report['averages_by_day_of_the_week'],
            ]);

        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }
}
