<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
     protected $fillable = [
        'id' ,
        'code' ,
        'name',
        'description',
        'sale_price', 
        'recipe_cost_per_portion',
        'is_active', 
        'created_at', 
        'updated_at',
    ];
}
