<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {   
        // Sử dụng DB facade để join và tính tổng số lượng đã bán
        $query = Product::withoutGlobalScope('active')
            ->with('category')
            ->select('products.*')
            ->selectRaw('IFNULL(SUM(CASE WHEN orders.status = "completed" THEN order_items.quantity ELSE 0 END), 0) as total_quantity')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.description', 'products.price',
                    'products.image', 'products.category_id', 'products.is_active', 'products.created_at', 'products.updated_at');

        // Tìm kiếm theo từ khóa
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.description', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Sắp xếp
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('products.created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('products.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('products.name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('products.price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('products.price', 'desc');
                break;
            case 'sales_desc':
                $query->orderBy('total_quantity', 'desc');
                break;
            case 'sales_asc':
                $query->orderBy('total_quantity', 'asc');
                break;
            default:
                $query->orderBy('products.created_at', 'desc');
        }
        
        $products = $query->paginate(6)->withQueryString();
        $products->getCollection()->transform(function ($product) {
            $product->load('promotions'); // 👈 load lại quan hệ promotions
            return $product;
        });

        $categories = Category::withoutGlobalScope('active')->get();
        $promotions = Promotion::where('is_active', true)->get();
            // dd($products->first()->promotions);


        return view('admin.products.index', compact('products', 'categories','promotions'));
    }

    /**
     * Hiển thị form tạo sản phẩm
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        // Xử lý slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Xử lý trạng thái
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Xử lý hình ảnh đại diện
        if ($request->hasFile('image')) {
            // Đảm bảo thư mục products tồn tại
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Lưu sản phẩm
        try {
            $product = Product::create($data);
            return redirect()->route('admin.products.index')
                ->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            // Xóa ảnh đã tải lên nếu có lỗi
            if (isset($data['image']) && Storage::disk('public')->exists($data['image'])) {
                Storage::disk('public')->delete($data['image']);
            }

            return back()->withInput()
                ->with('error', 'Đã xảy ra lỗi khi thêm sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show(Product $product)
    {
        // Remove global scope to show even inactive products
        $product = Product::withoutGlobalScope('active')
            ->with(['category', 'reviews.user'])
            ->findOrFail($product->id);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit(Product $product)
    {
        $categories = Category::withoutGlobalScope('active')->get();
        // Remove global scope to edit even inactive products
        $product = Product::withoutGlobalScope('active')->findOrFail($product->id);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(ProductRequest $request, Product $product)
    {
        // Remove global scope to update even inactive products
        $product = Product::withoutGlobalScope('active')->findOrFail($product->id);

        $data = $request->validated();

        // Xử lý slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Xử lý trạng thái
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Xử lý hình ảnh đại diện
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Đảm bảo thư mục products tồn tại
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Cập nhật sản phẩm
        try {
            $product->update($data);
            return redirect()->route('admin.products.index')
                ->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            // Nếu có lỗi và đã tải lên ảnh mới, xóa ảnh đó
            if (isset($data['image']) && Storage::disk('public')->exists($data['image'])) {
                Storage::disk('public')->delete($data['image']);
            }

            return back()->withInput()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy(Product $product)
    {
        // Xóa hình ảnh nếu có
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    /**
     * Xử lý hành động hàng loạt cho sản phẩm
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $action = $request->input('action');
        $selectedIds = $request->input('product_ids');

        switch ($action) {
            case 'delete':
                // Lấy danh sách sản phẩm để xóa ảnh
                $products = Product::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->get();

                // Xóa ảnh trước khi xóa sản phẩm
                foreach ($products as $product) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                }

                // Xóa sản phẩm
                Product::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->delete();
                $message = 'Đã xóa ' . count($selectedIds) . ' sản phẩm thành công';
                break;

            case 'activate':
                Product::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->update(['is_active' => true]);
                $message = 'Đã kích hoạt ' . count($selectedIds) . ' sản phẩm';
                break;

            case 'deactivate':
                Product::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->update(['is_active' => false]);
                $message = 'Đã ẩn ' . count($selectedIds) . ' sản phẩm';
                break;

            default:
                return back()->with('error', 'Hành động không hợp lệ');
        }

        return back()->with('success', $message);
    }
    public function applyPromotion(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $promotion = Promotion::findOrFail($request->promotion_id);

        Product::whereIn('id', $request->product_ids)->each(function ($product) use ($promotion) {
            $product->promotions()->sync([$promotion->id]);
        });

        return redirect()->back()->with('success', '🎉 Khuyến mãi đã được áp dụng cho sản phẩm đã chọn!');
    }
    public function removePromotion(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $request->product_ids)->each(function ($product) {
            $product->promotions()->detach(); // 👈 Gỡ toàn bộ khuyến mãi
        });

        return redirect()->back()->with('success', '❌ Khuyến mãi đã được tắt cho sản phẩm đã chọn!');
    }
}
