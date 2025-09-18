<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredients;
use App\Models\Product;
use App\Models\RecipeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['recipeItems.ingredient'])->get();

        foreach ($products as $product) {
        $totalCost = 0;

        foreach ($product->recipeItems as $item) {
            $ingredient = $item->ingredient;
            $unit = strtolower($ingredient->base_unit);
            $rawQty = $item->quantity_per_portion_base;
            $rawCost = $ingredient->suggested_unit_cost;

            // Quy đổi số lượng sang đơn vị hiển thị
            if ($unit === 'gram' || $unit === 'g') {
                $displayQty = $rawQty / 1000;
                $displayUnit = 'kg';
                $unitCost = $rawCost / 1000; // giá trên 1g
            } elseif ($unit === 'ml') {
                $displayQty = $rawQty / 1000;
                $displayUnit = 'l';
                $unitCost = $rawCost / 1000; // giá trên 1ml
            } else {
                $displayQty = $rawQty;
                $displayUnit = $ingredient->base_unit;
                $unitCost = $rawCost;
            }
                        // Tính chi phí nguyên liệu
                $cost = $rawQty * $unitCost;

                // Gán dữ liệu cho view
                $item->display_quantity = number_format($displayQty, 2); // ví dụ: 1.50
                $item->display_unit = $displayUnit;
                $item->cost = $cost;

                $totalCost += $cost;
            }

            $product->ingredient_cost = $totalCost;
        }



        return view('admin.recipes.index',compact('products'));
    }
   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('recipeItems')->findOrFail($id);
        $ingredients = Ingredients::where('is_active', true)->get();

        return view('admin.recipes.edit', compact('product', 'ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
        $product = Product::findOrFail($id);

        // Xóa toàn bộ công thức cũ
        RecipeItem::where('menu_item_id', $product->id)->delete();

        // Tạo lại công thức mới
        foreach ($request->ingredients as $item) {
            if (!$item['ingredient_id'] || !$item['quantity_per_portion_base']) continue;

            RecipeItem::create([
                'menu_item_id' => $product->id,
                'ingredient_id' => $item['ingredient_id'],
                'quantity_per_portion_base' => $item['quantity_per_portion_base'],
                'note' => $item['note'] ?? null,
            ]);
        }
    });

    return redirect()->route('admin.recipes.index')->with('success', 'Cập nhật công thức thành công!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
