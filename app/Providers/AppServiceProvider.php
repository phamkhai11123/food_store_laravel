<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sử dụng Tailwind cho phân trang
        Paginator::useTailwind();

        // Đặt độ dài mặc định cho chuỗi trong migration
        Schema::defaultStringLength(191);

        // Đăng ký các Blade component
        Blade::componentNamespace('App\\View\\Components\\Ui', 'ui');
        Blade::componentNamespace('App\\View\\Components\\Layouts', 'layouts');

        // Route model binding cho Product
        Route::bind('product', function ($value) {
            return Product::withoutGlobalScope('active')
                ->with(['category', 'reviews.user'])
                ->findOrFail($value);
        });
    }
}
