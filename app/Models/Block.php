<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        "name",
        "description",
        "status_block"
    ];

    public $timestamps = false;
}
