<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TableReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone',
        'arrival_time',
        'guest_count',
        'note',
        'status',
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
    ];
}