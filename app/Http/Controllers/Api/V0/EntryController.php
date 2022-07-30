<?php

namespace App\Http\Controllers\Api\V0;

use App\Interfaces\Services\IEntryService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class EntryController
{

    public function __construct(private IEntryService $service)
    {
    }

    public function insertEntry($enrollment_id){
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

    public function getEntries(Request $request){
        try {
            $entries = $this->service->getEntriesByUsername($request->user()->uid);

            return ApiResponse::ok($entries);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

}
