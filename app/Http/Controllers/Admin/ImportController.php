<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IngredientImport;
use App\Models\IngredientImportDetail;
use App\Models\Ingredients;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {

    //     $imports = IngredientImport::with('details')->orderByDesc('import_date')->get();


    //     return view('admin.import.index',compact('imports'));
    // }
    public function index(Request $request)
    {
        $query = IngredientImport::query();
        

        // 🔍 Lọc theo mã đơn
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // 🔍 Lọc theo nhà cung cấp
        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
        }

        // 📅 Lọc theo ngày nhập
        if ($request->filled('from')) {
            $query->whereDate('import_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('import_date', '<=', $request->to);
        }

        // 🔁 Sắp xếp theo tổng tiền
        switch ($request->sort) {
            case 'asc':
                $query->orderBy('total_cost', 'asc');
                break;
            case 'desc':
                $query->orderBy('total_cost', 'desc');
                break;
            default:
                $query->orderBy('import_date', 'desc'); // mặc định: mới nhất
        }

            $query->with('details.ingredient');

    // 👉 Lấy toàn bộ dữ liệu (không dùng paginate nếu cần sắp xếp thủ công)
        $imports = $query->get();

        $imports = $imports->map(function ($import) {
        $adjustedTotal = 0;
        foreach ($import->details as $detail) {
            $unit = $detail->ingredient->base_unit;
            $qty = in_array($unit, ['ml', 'g']) ? $detail->quantity / 1000 : $detail->quantity;
            $adjustedTotal += $qty * $detail->unit_price;
        }
        $import->adjusted_total = $adjustedTotal;
        return $import;
    });

    // 👉 Sắp xếp theo adjusted_total nếu có yêu cầu
    if ($request->sort === 'asc') {
        $imports = $imports->sortBy('adjusted_total');
    } elseif ($request->sort === 'desc') {
        $imports = $imports->sortByDesc('adjusted_total');
    }

    // Eager load chi tiết
    // $imports = $query->with('details.ingredient')->paginate(50);

    return view('admin.import.index', compact('imports'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ingredients = Ingredients::all();
        $suppliers = [
            'Công ty TNHH Thực phẩm An Khang',
            'Công ty Cổ phần Nguyên liệu Việt',
            'Công ty TNHH Thương mại Minh Phát',
            'Công ty TNHH Xuất nhập khẩu Hương Việt',
            'Công ty TNHH Sản xuất & Phân phối Nam Sơn',
        ];

        return view('admin.import.create',compact('ingredients','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
        
    public function store(Request $request)
        {
            DB::transaction(function () use ($request) {
                // Tạo mã đơn tự động (ví dụ: IMP20250912-001)
                $latestId = IngredientImport::max('id') + 1;
                $code = 'IMP' . now()->format('Ymd') . '-' . str_pad($latestId, 3, '0', STR_PAD_LEFT);

                // Tạo phiếu nhập
                $import = IngredientImport::create([
                    'code' => $code,
                    'import_date' => now(),
                    'supplier' => $request->supplier,
                    'note' => null,
                    'total_cost' => 0, // tạm thời
                ]);

                $total = 0;

                foreach ($request->ingredients as $item) {
                    if (!$item['id'] || !$item['quantity'] || !$item['unit_price']) continue;

                    $lineTotal = $item['quantity'] * $item['unit_price'];
                    $total += $lineTotal;
                   

                   
                    $ingredient = Ingredients::find($item['id']);

                    if ($ingredient) {
                        // Lưu chi tiết nhập
                        IngredientImportDetail::create([
                            'ingredient_import_id' => $import->id,
                            'ingredient_id' => $ingredient->id,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                        ]);

                        // Cập nhật tồn kho nếu có theo dõi
                        if ($ingredient->track_stock) {
                            $ingredient->increment('track_stock', $item['quantity']);
                        }

                        // ✅ Ghi nhận lịch sử nhập kho
                        InventoryTransaction::create([
                            'ingredient_id' => $ingredient->id,
                            'type' => 'import',
                            'quantity_base' => $item['quantity'],
                            // 'unit' => $ingredient->base_unit,
                            'performed_at' => now(),
                            'note' => "Nhập kho từ phiếu #{$import->code}",
                            'ref_id' => $import->id,
                        ]);
                    }
                }

                // Cập nhật tổng tiền
                $import->update(['total_cost' => $total]);
            });

            return redirect()->route('admin.import.index')->with('success', 'Nhập hàng thành công!');
        }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $import = IngredientImport::with(['details.ingredient'])->findOrFail($id);

        return view('admin.import.show', compact('import'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            DB::transaction(function () use ($id) {
            $import = IngredientImport::with('details')->findOrFail($id);
            foreach ($import->details as $detail) {
                $ingredient = Ingredients::find($detail->ingredient_id);
                if ($ingredient) {
                    // Trừ lại số lượng tồn kho
                    $ingredient->track_stock -= $detail->quantity;

                    // Đảm bảo không âm
                    if ($ingredient->track_stock < 0) {
                        $ingredient->track_stock = 0;
                    }

                    $ingredient->save();
                }

                // Xóa chi tiết nhập
                $detail->delete();
            }

            // Xóa phiếu nhập
            $import->delete();
        });

        return redirect()->route('admin.import.index')->with('success', 'Đã xóa đơn nhập thành công!');
    }
}
