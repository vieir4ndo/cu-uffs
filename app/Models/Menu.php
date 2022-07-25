<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        "salad_1",
        "salad_2",
        "salad_3",
        "grains_1",
        "grains_2",
        "grains_3",
        "side_dish",
        "mixture",
        "vegan_mixture",
        "dessert",
        "date",
        'ru_employee_id',
    ];
}
