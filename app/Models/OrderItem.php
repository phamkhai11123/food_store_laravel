<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'product_name',
    ];

    /**
     * Lấy thông tin đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Lấy thông tin sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Lấy thành tiền của sản phẩm
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Lấy thông tin đánh giá của sản phẩm trong đơn hàng
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
