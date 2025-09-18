<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientImportDetail extends Model
{
    protected $fillable = [
        'ingredient_import_id', 'ingredient_id', 'quantity', 'unit_price'
    ];

    public function import()
    {
        return $this->belongsTo(IngredientImport::class, 'ingredient_import_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }
}