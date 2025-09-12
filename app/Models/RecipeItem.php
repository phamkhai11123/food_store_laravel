<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'menu_item_id',
        'ingredient_id',
        'quantity_per_portion_base',
        'note',
    ];

    public function menuItem() { return $this->belongsTo(MenuItems::class); }
    public function ingredient() { return $this->belongsTo(Ingredients::class); }
}
