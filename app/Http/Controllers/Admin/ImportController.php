<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IngredientImport;
use App\Models\IngredientImportDetail;
use App\Models\Ingredients;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
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
        // Biểu đồ theo ngày (7 ngày gần nhất)


        // 📅 Biểu đồ theo ngày
        $dailyLabels = [];
        $dailyDatasets = [[
            'label' => 'Tổng tiền nhập theo ngày',
            'data' => [],
            'borderColor' => 'rgba(59,130,246,1)',
            'backgroundColor' => 'rgba(59,130,246,0.1)',
            'borderWidth' => 2,
            'tension' => 0.3,
            'fill' => true
        ]];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[] = $date->format('d/m');
            $total = IngredientImport::whereDate('import_date', $date->toDateString())->sum('total_cost');
            $dailyDatasets[0]['data'][] = $total;
        }

        // 📆 Biểu đồ theo tháng
        $monthLabels = [];
        $monthlyDatasets = [[
            'label' => 'Tổng tiền nhập theo tháng',
            'data' => [],
            'borderColor' => 'rgba(34,197,94,1)',
            'backgroundColor' => 'rgba(34,197,94,0.1)',
            'borderWidth' => 2,
            'tension' => 0.3,
            'fill' => true
        ]];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabels[] = $date->format('m/Y');
            $total = IngredientImport::whereMonth('import_date', $date->month)
                ->whereYear('import_date', $date->year)
                ->sum('total_cost');
            $monthlyDatasets[0]['data'][] = $total;
        }

        // 📈 Biểu đồ theo năm
        $yearLabels = [];
        $yearlyDatasets = [[
            'label' => 'Tổng tiền nhập theo năm',
            'data' => [],
            'borderColor' => 'rgba(239,68,68,1)',
            'backgroundColor' => 'rgba(239,68,68,0.1)',
            'borderWidth' => 2,
            'tension' => 0.3,
            'fill' => true
        ]];

        for ($i = 2; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $yearLabels[] = (string)$year;
            $total = IngredientImport::whereYear('import_date', $year)->sum('total_cost');
            $yearlyDatasets[0]['data'][] = $total;
        }
        
        return view('admin.import.index', compact(
            'imports',
            'dailyLabels', 'dailyDatasets',
            'monthLabels', 'monthlyDatasets',
            'yearLabels', 'yearlyDatasets'
        ));
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
            // 🔢 Tạo mã đơn tự động (ví dụ: IMP20250922-001)
            $latestId = IngredientImport::max('id') + 1;
            $code = 'IMP' . now()->format('Ymd') . '-' . str_pad($latestId, 3, '0', STR_PAD_LEFT);

            // 🧾 Tạo phiếu nhập ban đầu
            $import = IngredientImport::create([
                'code' => $code,
                'import_date' => now(),
                'supplier' => $request->supplier,
                'note' => null,
                'total_cost' => 0, // sẽ cập nhật sau
            ]);

            $total = 0;

            foreach ($request->ingredients as $item) {
                if (empty($item['id']) || empty($item['quantity']) || empty($item['unit_price'])) {
                    continue;
                }

                $ingredient = Ingredients::find($item['id']);
                if (!$ingredient) continue;

                $qty = $item['quantity'];
                $unit = $ingredient->base_unit;

                // ⚖️ Quy đổi đơn vị nếu là g hoặc ml
                $adjustedQty = in_array($unit, ['g', 'ml']) ? $qty / 1000 : $qty;

                $lineTotal = $adjustedQty * $item['unit_price'];
                $total += $lineTotal;

                // 💾 Lưu chi tiết nhập
                IngredientImportDetail::create([
                    'ingredient_import_id' => $import->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $qty, // lưu số lượng gốc
                    'unit_price' => $item['unit_price'],
                ]);

                // 📦 Cập nhật tồn kho nếu có theo dõi
                if ($ingredient->track_stock) {
                    $ingredient->increment('track_stock', $qty);
                }

                // 🧾 Ghi nhận lịch sử nhập kho
                InventoryTransaction::create([
                    'ingredient_id' => $ingredient->id,
                    'type' => 'import',
                    'quantity_base' => $qty,
                    'performed_at' => now(),
                    'note' => "Nhập kho từ phiếu #{$import->code}",
                    'ref_id' => $import->id,
                ]);
            }

            // 💰 Cập nhật tổng tiền sau khi xử lý xong
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
