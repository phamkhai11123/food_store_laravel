<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'ingredient_id',
        'trx_type',
        'ref_table',
        'ref_id',
        'quantity_base',
        'performed_at',
        'note',
        'created_at',
    ];


}
