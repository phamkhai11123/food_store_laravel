<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    /**
     * Lấy danh sách sản phẩm thuộc danh mục
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the URL for the category image.
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
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
        }

        // Kiểm tra nếu hình ảnh mặc định tồn tại
        if (file_exists(public_path('images/default-category.png'))) {
            return asset('images/default-category.png');
        }

        // Nếu không có hình ảnh nào, trả về null để không gây lỗi
        return null;
    }
}
