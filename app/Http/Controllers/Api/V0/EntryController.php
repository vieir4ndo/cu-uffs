<?php

namespace App\Http\Controllers\Api\V0;

use App\Models\Api\ApiResponse;
use App\Services\EntryService;
use Exception;

class EntryController
{
    private EntryService $service;

    public function __construct(EntryService $service)
    {
        $this->service = $service;
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

    public function getEntries($uid){
        try {
            $entries = $this->service->getEntriesByUsername($uid);

            return ApiResponse::ok($entries);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

}
