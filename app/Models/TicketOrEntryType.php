<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOrEntryType extends Model
{
    protected $fillable = [
        'id',
        'description'
    ];

    public $timestamps = false;
}
