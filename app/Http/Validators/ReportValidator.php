<?php

namespace App\Http\Validators;

class ReportValidator
{
    public static function redirectReportRules()
    {
        return [
            "init_date" => ['required', 'date', 'before:final_date'],
            "final_date" => ['required', 'date', 'after:init_date']
        ];
    }
}
