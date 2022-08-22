<?php

namespace App\Http\Validators;

use App\Rules\DateRangeRule;

class ReportValidator
{
    public static function redirectReportRules($init_date, $final_date)
    {
        return [
            "init_date" => ['required', 'date', 'before:final_date'],
            "final_date" => ['required', 'date', 'after:init_date', new DateRangeRule($init_date, $final_date)],
        ];
    }
}
