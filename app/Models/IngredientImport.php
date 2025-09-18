<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientImport extends Model
{
    protected $fillable = [
        'code', 'import_date', 'supplier', 'note', 'total_cost'
    ];

    public function details()
    {
        return $this->hasMany(IngredientImportDetail::class);
    }
}