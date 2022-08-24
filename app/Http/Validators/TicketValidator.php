<?php

namespace App\Http\Validators;

class TicketValidator
{
    public static function insertTicketsRules()
    {
        return [
            'amount' => ['required', 'integer', 'min:0', 'not_in:0']
        ];
    }

    public static function insertTicketsWithEnrollmentIdRules(){
        return [
            'amount' => ['required','integer', 'min:0', 'not_in:0'],
            'enrollment_id' => ['required']
        ];
    }
}
