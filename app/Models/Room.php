<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        "name",
        "description",
        "capacity",
        "status_room",
        "responsable_id",
        "block_id"
    ];

    public $timestamps = false;

}
