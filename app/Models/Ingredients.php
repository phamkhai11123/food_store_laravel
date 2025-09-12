<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredients extends Model
{
   use HasFactory;

    protected $fillable = [
        'id' ,
        'sku' ,
        'name',
        'base_unit',
        'track_stock', 
        'suggested_unit_cost',
        'is_active', 
        'created_at', 
        'updated_at',
    ];

    public function recipeItems() {
        return $this->hasMany(RecipeItem::class);
    }

}
