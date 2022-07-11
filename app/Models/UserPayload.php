<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayload extends Model
{
    protected $fillable = [
        'uid',
        'status',
        'message',
        'payload',
        'operation'
    ];
}
