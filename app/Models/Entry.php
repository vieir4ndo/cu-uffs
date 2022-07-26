<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'date_time',
        'user_id'
    ];

    public $timestamps = false;
}
