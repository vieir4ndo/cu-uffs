<?php

namespace App\Interfaces\Services;

interface IEntryService
{
    function insertEntry($enrollment_id, $data);
    function getEntriesByUsername(string $uid);
    function generateReport($init_date, $final_date);
}