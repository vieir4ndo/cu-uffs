<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOperationStatus extends Model
{
    protected $fillable = [
        'id',
        'description'
    ];

    public $timestamps = false;
}
