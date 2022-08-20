<?php

namespace App\Http\Controllers\Api\V0;

use App\Interfaces\Services\IEntryService;
use App\Models\Api\ApiResponse;
use App\Validators\ReportValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntryController
{
    private IEntryService $service;

    public function __construct(IEntryService $service)
    {
        $this->service = $service;
    }

    public function insertEntry($enrollment_id)
    {
        try {
            $data = [
                "date_time" => now(),
            ];

            $this->service->insertEntry($enrollment_id, $data);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getEntries(Request $request)
    {
        try {
            $entries = $this->service->getEntriesByUsername($request->user()->uid);

            return ApiResponse::ok($entries);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getReport(Request $request)
    {
        try {
            $dates = [
                'init_date' => $request->init_date,
                'final_date' => $request->final_date
            ];

            $validation = Validator::make($dates, ReportValidator::redirectReportRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
             }

            $entries = $this->service->generateReport($request->init_date, $request->final_date);

            return ApiResponse::ok($entries);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

}
