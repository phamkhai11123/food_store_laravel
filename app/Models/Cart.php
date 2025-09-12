<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    /**
     * Lấy thông tin người dùng sở hữu giỏ hàng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy thông tin sản phẩm trong giỏ hàng
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Lấy thành tiền của sản phẩm trong giỏ hàng
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->product->price;
    }
}
