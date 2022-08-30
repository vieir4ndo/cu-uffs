<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Http\Validators\ReportValidator;
use App\Interfaces\Services\IReportService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    private IReportService $service;

    public function __construct(IReportService $service)
    {
        $this->service = $service;
    }

    public function getEntryReport(Request $request)
    {
        try {
            $dates = [
                'init_date' => $request->init_date,
                'final_date' => $request->final_date
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules($request->init_date, $request->final_date));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $entries = $this->service->generateEntryReport($request->init_date, $request->final_date);

            return ApiResponse::ok($entries);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getTicketReport(Request $request)
    {
        try {

            $dates = [
                'init_date' => $request->init_date,
                'final_date' => $request->final_date
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules($request->init_date, $request->final_date));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $tickets = $this->service->generateTicketReport($request->init_date, $request->final_date);

            return ApiResponse::ok($tickets);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }
}
