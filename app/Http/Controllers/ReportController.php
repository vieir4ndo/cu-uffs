<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Api\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Services\IEntryService;

class ReportController extends Controller
{
    private IEntryService $service;

    public function __construct(IEntryService $service)
    {
        $this->service = $service;
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

            $validation = Validator::make($dates, $this->redirectEntryReportRules($request->initDate, $request->finalDate));

            // if ($validation->fails()) {
            // $errors = $validation->errors()->all(); with errors
            // }

            $report = $this->service->generateReport($request->initDate, $request->finalDate);

            return view('restaurant.report.entry', $report);

        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function redirectEntryReportRules($initDate, $finalDate)
    {
        $initDate = Carbon::parse($initDate);
        $finalDate = Carbon::parse($finalDate);
        return [
            "init_date" => ['required', 'date'],
            "final_date" => ['required', 'date']
        ];
    }
}
