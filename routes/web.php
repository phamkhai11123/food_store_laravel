<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\IngredientController as AdminIngredientController;
use App\Http\Controllers\Admin\InventoryTransactionController as AdminInventoryTransactionController;
use App\Http\Controllers\Admin\RecipeItemController as AdminRecipeItemController;
use App\Http\Controllers\Admin\MenuItemController as AdminMenuItemController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ImportController as AdminImportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ và trang sản phẩm
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Thông tin cá nhân
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Đơn hàng
    Route::prefix('orders')->name('shop.orders.')->group(function () {
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::put('/{order}/complete', [OrderController::class, 'complete'])->name('complete');
    });

    // Đánh giá sản phẩm
    Route::prefix('reviews')->name('shop.reviews.')->group(function () {
        Route::get('/order-items/{orderItem}/create', [ReviewController::class, 'create'])->name('create');
        Route::post('/order-items/{orderItem}', [ReviewController::class, 'store'])->name('store');
        Route::get('/order-items/{orderItem}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/order-items/{orderItem}', [ReviewController::class, 'update'])->name('update');
    });
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::resource('categories', AdminCategoryController::class);
    Route::post('/categories/bulk-action', [AdminCategoryController::class, 'bulkAction'])->name('categories.bulk-action');

    // Quản lý sản phẩm
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    Route::post('/products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::post('/products/delete-gallery-image', [AdminProductController::class, 'deleteGalleryImage'])->name('products.delete-gallery-image');

    // Quản lý đơn hàng
    Route::get('/orders', [AdminOrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrdersController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [AdminOrdersController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [AdminOrdersController::class, 'update'])->name('orders.update');
    Route::patch('/orders/{order}/status', [AdminOrdersController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/mark-paid', [AdminOrdersController::class, 'markPaid'])->name('orders.mark-paid');
    Route::patch('/orders/{order}/mark-unpaid', [AdminOrdersController::class, 'markUnpaid'])->name('orders.mark-unpaid');
    Route::post('/orders/bulk-update', [AdminOrdersController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::post('/orders/bulk-action', [AdminOrdersController::class, 'bulkAction'])->name('orders.bulk-action');
    Route::get('/orders/{order}/print', [AdminOrdersController::class, 'print'])->name('orders.print');

    // quan ly nguyen lieu
    Route::get('/ingredients',[AdminIngredientController::class,'index'])->name('ingredients.index');
    Route::get('/ingredients/create',[AdminIngredientController::class,'create'])->name('ingredients.create');
    Route::post('/ingredients/store',[AdminIngredientController::class,'store'])->name('ingredients.store');
    Route::get('/ingredients/{ingredient}/edit', [AdminIngredientController::class, 'edit'])->name('ingredients.edit');
    Route::put('/ingredients/{ingredient}', [AdminIngredientController::class, 'update'])->name('ingredients.update');
    Route::delete('/ingredients/{ingredient}', [AdminIngredientController::class, 'destroy'])->name('ingredients.destroy');
    // Route::get('/recipe-item',[AdminRecipeItemController::class,'index'])->name('.index');
    // Route::get('/inventory-transaction',[AdminInventoryTransactionController::class,'index'])->name('ingredients.index');

    // Quản lý nhập hàng
    Route::get('/import', [AdminImportController::class, 'index'])->name('import.index');
    Route::get('/import/create', [AdminImportController::class, 'create'])->name('import.create'); // Hiển thị form nhập hàng
    Route::post('/import', [AdminImportController::class, 'store'])->name('import.store'); // Xử lý lưu phiếu nhập
    Route::get('/import/{id}', [AdminImportController::class, 'show'])->name('import.show');
    Route::delete('/import/{id}', [AdminImportController::class, 'destroy'])->name('import.destroy');

    //Quản lý công thức(recipe)
    Route::get('/recipes', [AdminRecipeItemController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/{id}', [AdminRecipeItemController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{id}', [AdminRecipeItemController::class, 'update'])->name('recipes.update');

    // Quản lý hao hụt 
    Route::get('/inventory', [AdminInventoryTransactionController::class, 'index'])->name('inventory.index');
    // Quản lý người dùng
    Route::resource('users', UserController::class);
});
