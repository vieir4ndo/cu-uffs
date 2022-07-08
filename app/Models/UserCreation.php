<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreation extends Model
{
    protected $table = 'user_creation';

    protected $fillable = [
        'uid',
        'status',
        'message',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
