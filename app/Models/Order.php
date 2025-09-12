<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'shipping_fee',
        'total',
        'note',
    ];

    /**
     * Lấy thông tin người dùng đặt hàng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy danh sách các sản phẩm trong đơn hàng
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias cho orderItems - để tương thích với view
     */
    public function items()
    {
        return $this->orderItems();
    }

    /**
     * Lấy danh sách sản phẩm trong đơn hàng
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Lấy trạng thái đơn hàng dưới dạng văn bản tiếng Việt
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Đang xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao hàng',
            'completed' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Kiểm tra xem đơn hàng đã giao hàng chưa
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }
}
