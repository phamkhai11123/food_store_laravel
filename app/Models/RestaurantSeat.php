<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSeat extends Model
{
    protected $fillable = [
        'total_seats',
        'available_seats',
    ];
}