<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CCR extends Model
{
    protected $fillable = [
        "name",
        "status_ccr"
    ];

    protected $table = 'ccr';
    public $timestamps = false;
}
