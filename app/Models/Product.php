<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\RecipeItem;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }
    

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class, 'menu_item_id');
    }

    /**
     * Lấy danh mục của sản phẩm
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Lấy danh sách đánh giá của sản phẩm
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Lấy danh sách đơn hàng chứa sản phẩm
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Lấy giỏ hàng chứa sản phẩm
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the URL for the product image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if the image is a full URL (starts with http)
            if (strpos($this->image, 'http') === 0) {
                return $this->image;
            }
            // Otherwise, assume it's stored in the storage/app/public directory
            return asset('storage/' . $this->image);
        }

        // Return a default placeholder image service
        return 'https://via.placeholder.com/150?text=No+Image';
    }

    /**
     * Lấy điểm đánh giá trung bình
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    /**
     * Lấy số lượng đánh giá
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}
