<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'date_time',
        'user_id',
        'amount',
        'third_party_cashier_employee_id'
    ];
}
