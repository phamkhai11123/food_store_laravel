<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'rating',
        'comment',
    ];

    /**
     * Lấy thông tin người dùng đánh giá
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy thông tin sản phẩm được đánh giá
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Lấy thông tin order item liên quan đến đánh giá
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
