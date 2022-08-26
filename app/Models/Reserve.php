<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    protected $fillable = [
        "begin",
        "end",
        "description",
        "status",
        "observation",
        "locator_id",
        "room_id",
        "ccr_id"
    ];

    public $timestamps = false;

}
