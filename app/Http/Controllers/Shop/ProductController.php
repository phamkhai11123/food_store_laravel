<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ReviewRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with('promotions')->where('is_active', true);

        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('id', $request->category)
                  ->where('is_active', true); // Only include active categories
            });
        } else {
            // Only include products from active categories
            $query->whereHas('category', function($q) {
                $q->where('is_active', true);
            });
        }
        if ($request->sale == 1) {
            $query->whereHas('promotions', function ($q) {
                $now = now();
                $q->where('is_active', true)
                ->where(function ($q2) use ($now) {
                    $q2->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($q2) use ($now) {
                    $q2->whereNull('end_date')->orWhere('end_date', '>=', $now);
                });
            });
        }

        // Tìm kiếm theo từ khóa
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sắp xếp
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('shop.products.index', compact('products', 'categories'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with(['category', 'reviews.user'])
            ->withoutGlobalScope('active')
            ->where('is_active', true)
            ->findOrFail($id);

        // Kiểm tra nếu danh mục bị ẩn
        if (!$product->category->is_active) {
            abort(404);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true) // Only active related products
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('shop.products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Thêm đánh giá sản phẩm
     */
    public function review(ReviewRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            // Cập nhật đánh giá
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            $message = 'Cập nhật đánh giá thành công!';
        } else {
            // Thêm đánh giá mới
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            $message = 'Thêm đánh giá thành công!';
        }

        return redirect()->back()->with('success', $message);
    }
}
