<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredients;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingredients::query();

        // 🔍 Tìm kiếm theo tên
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // ✅ Lọc theo trạng thái hoạt động
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // ⚖️ Lọc theo đơn vị
        if ($request->filled('base_unit')) {
            $query->where('base_unit', $request->base_unit);
        }
            if ($request->filled('stock_status')) {
            $query->where(function ($q) use ($request) {
                if ($request->stock_status === 'low') {
                    $q->where(function ($sub) {
                        $sub->where(function ($s) {
                            $s->whereIn('base_unit', ['ml', 'g'])
                            ->where('track_stock', '<', 2000);
                        })->orWhere(function ($s) {
                            $s->where('base_unit', 'pc')
                            ->where('track_stock', '<', 10);
                        });
                    });
                } elseif ($request->stock_status === 'enough') {
                    $q->where(function ($sub) {
                        $sub->where(function ($s) {
                            $s->whereIn('base_unit', ['ml', 'g'])
                            ->where('track_stock', '>=', 2000);
                        })->orWhere(function ($s) {
                            $s->where('base_unit', 'pc')
                            ->where('track_stock', '>=', 10);
                        });
                    });
                }
            });
        }
        // 👉 Sắp xếp mặc định theo tên
        // $query->orderBy('name');

        switch ($request->sort_stock) {
            case 'asc':
                $query->orderBy('track_stock', 'asc');
                break;
            case 'desc':
                $query->orderBy('track_stock', 'desc');
                break;
            default:
                $query->orderBy('name'); // mặc định sắp xếp theo tên
        }


        // 👉 Dùng phân trang để tránh tải toàn bộ
        $ingredients = $query->paginate(50);

        return view('admin.ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ingredients = $request->input('ingredients', []);
        foreach ($ingredients as $data) {
            Ingredients::create([
                'sku' => $data['sku'],
                'name' => $data['name'],
                'base_unit' => $data['base_unit'],
                'track_stock' => $data['track_stock'] ?? 0,
                'suggested_unit_cost' => $data['suggested_unit_cost'] ?? 0,
                'is_active' => $data['is_active'] ?? 1,
            ]);
        }

        return redirect()->route('admin.ingredients.index')->with('success', 'Đã thêm nguyên liệu thành công!');

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredients $ingredient)
    {
        $ingredient = Ingredients::findOrFail($ingredient->id);
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredients $ingredient)
    {
        // ✅ Validate dữ liệu đầu vào
        $validated = $request->validate([
            'sku' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'base_unit' => 'required|in:g,ml,pc',
            'track_stock' => 'nullable|numeric|min:0',
            'suggested_unit_cost' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        // ✅ Cập nhật dữ liệu
        $ingredient->update($validated);

        // ✅ Chuyển hướng về danh sách kèm thông báo
        return redirect()->route('admin.ingredients.index')
                        ->with('success', 'Nguyên liệu đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredients $ingredient)
    {
        $ingredient->delete();

        return redirect()->route('admin.ingredients.index')
                        ->with('success', 'Nguyên liệu đã được xóa thành công!');
    }
    public function import(){
        
    }
}
