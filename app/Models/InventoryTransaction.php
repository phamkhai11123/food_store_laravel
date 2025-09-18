<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredients;

class InventoryTransaction extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity_base',
        'unit',
        'unit_cost',
        'ref_id',
        'user_id',
        'performed_at',
        'note',
    ];
    
    public function ingredients()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }

    // 🔗 Quan hệ với người thực hiện
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Quan hệ với đơn hàng hoặc phiếu nhập (nếu có)
    public function order()
    {
        return $this->belongsTo(Order::class, 'ref_id');
    }


}
