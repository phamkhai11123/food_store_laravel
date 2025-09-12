<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục
     */
    public function index(Request $request)
    {
        $query = Category::withoutGlobalScope('active')->withCount('products');

        // Tìm kiếm theo từ khóa
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Sắp xếp
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'products_count':
                $query->orderBy('products_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $categories = $query->paginate(10)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo danh mục
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Lưu danh mục mới
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        // Xử lý slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Xử lý is_active
        $data['is_active'] = $request->has('is_active');

        // Xử lý hình ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa danh mục
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Cập nhật danh mục
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        // Xử lý slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Xử lý is_active
        $data['is_active'] = $request->has('is_active');

        // Xử lý hình ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang chứa sản phẩm.');
        }

        // Xóa hình ảnh nếu có
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Xử lý hành động hàng loạt
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $action = $request->input('action');
        $selectedIds = $request->input('category_ids');

        switch ($action) {
            case 'delete':
                // Lấy danh sách categories để xóa ảnh
                $categories = Category::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->get();

                // Xóa ảnh trước khi xóa danh mục
                foreach ($categories as $category) {
                    if ($category->image) {
                        Storage::disk('public')->delete($category->image);
                    }
                }

                // Xóa danh mục
                Category::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->delete();
                $message = 'Đã xóa ' . count($selectedIds) . ' danh mục thành công';
                break;

            case 'activate':
                Category::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->update(['is_active' => true]);
                $message = 'Đã kích hoạt ' . count($selectedIds) . ' danh mục';
                break;

            case 'deactivate':
                Category::withoutGlobalScope('active')
                    ->whereIn('id', $selectedIds)
                    ->update(['is_active' => false]);
                $message = 'Đã ẩn ' . count($selectedIds) . ' danh mục';
                break;

            default:
                return back()->with('error', 'Hành động không hợp lệ');
        }

        return back()->with('success', $message);
    }
}
