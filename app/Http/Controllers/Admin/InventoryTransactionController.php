<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredients;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
   
    public function index(Request $request)
    {
        $query = InventoryTransaction::query();

        // 🔍 Tìm kiếm theo tên nguyên liệu
        if ($request->filled('name')) {
            $query->whereHas('ingredients', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // 📅 Lọc theo ngày thực hiện
        if ($request->filled('from')) {
            $query->whereDate('performed_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('performed_at', '<=', $request->to);
        }

        // 🔁 Lọc theo loại giao dịch
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 🔁 Lọc theo đơn vị nguyên liệu
        if ($request->filled('unit')) {
            $query->whereHas('ingredients', function ($q) use ($request) {
                $q->where('base_unit', $request->unit);
            });
        }

        // 🔁 Sắp xếp theo số lượng
        switch ($request->sort) {
            case 'asc':
                $query->orderBy('quantity_base', 'asc');
                break;
            case 'desc':
                $query->orderBy('quantity_base', 'desc');
                break;
            default:
                $query->orderBy('performed_at', 'desc'); // mặc định: mới nhất
        }

        // Eager load nguyên liệu và đơn hàng
        $transactions = $query->with('ingredients', 'order')->paginate(50);
        
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subDays($i)->format('d/m');
        }

        $ingredients = Ingredients::all();

        $importDatasets = [];
        $exportDatasets = [];

        foreach ($ingredients as $ingredient) {
            $importData = [];
            $exportData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->toDateString();

                $importQty = InventoryTransaction::where('ingredient_id', $ingredient->id)
                    ->where('type', 'import')
                    ->whereDate('performed_at', $date)
                    ->sum('quantity_base');

                $exportQty = InventoryTransaction::where('ingredient_id', $ingredient->id)
                    ->whereIn('type', ['export', 'loss'])
                    ->whereDate('performed_at', $date)
                    ->sum('quantity_base');

                $importData[] = $importQty;
                $exportData[] = $exportQty;
            }

            if (array_sum($importData) > 0) {
                $importDatasets[] = [
                    'label' => $ingredient->name,
                    'data' => $importData,
                    'borderColor' => 'rgba(34,197,94,1)',
                    'backgroundColor' => 'rgba(34,197,94,0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false
                ];
            }

            if (array_sum($exportData) > 0) {
                $exportDatasets[] = [
                    'label' => $ingredient->name,
                    'data' => $exportData,
                    'borderColor' => 'rgba(239,68,68,1)',
                    'backgroundColor' => 'rgba(239,68,68,0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false
                ];
            }
        }

        $importChart = [
            'labels' => $labels,
            'datasets' => $importDatasets
        ];

        $exportChart = [
            'labels' => $labels,
            'datasets' => $exportDatasets
        ];

        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthLabels[] = Carbon::now()->subMonths($i)->format('m/Y');
        }

        $importMonthlyDatasets = [];

        foreach ($ingredients as $ingredient) {
            $data = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $qty = InventoryTransaction::where('ingredient_id', $ingredient->id)
                    ->where('type', 'import')
                    ->whereMonth('performed_at', $date->month)
                    ->whereYear('performed_at', $date->year)
                    ->sum('quantity_base');

                $data[] = $qty;
            }

            if (array_sum($data) > 0) {
                $importMonthlyDatasets[] = [
                    'label' => $ingredient->name,
                    'data' => $data,
                    'borderColor' => 'rgba(34,197,94,1)',
                    'backgroundColor' => 'rgba(34,197,94,0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false
                ];
            }
        }
        $exportMonthlyDatasets = [];

        foreach ($ingredients as $ingredient) {
            $data = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $qty = InventoryTransaction::where('ingredient_id', $ingredient->id)
                    ->whereIn('type', ['export', 'loss'])
                    ->whereMonth('performed_at', $date->month)
                    ->whereYear('performed_at', $date->year)
                    ->sum('quantity_base');

                $data[] = $qty;
            }

            if (array_sum($data) > 0) {
                $exportMonthlyDatasets[] = [
                    'label' => $ingredient->name,
                    'data' => $data,
                    'borderColor' => 'rgba(239,68,68,1)',
                    'backgroundColor' => 'rgba(239,68,68,0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false
                ];
            }
        }

        $yearLabels = [];
        for ($i = 2; $i >= 0; $i--) {
            $yearLabels[] = Carbon::now()->subYears($i)->year;
        }

        $importYearlyDatasets = [];

        foreach ($ingredients as $ingredient) {
            $data = [];

            for ($i = 2; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $qty = InventoryTransaction::where('ingredient_id', $ingredient->id)
                    ->where('type', 'import')
                    ->whereYear('performed_at', $year)
                    ->sum('quantity_base');

                $data[] = $qty;
            }

            if (array_sum($data) > 0) {
                $importYearlyDatasets[] = [
                    'label' => $ingredient->name,
                    'data' => $data,
                    'borderColor' => 'rgba(34,197,94,1)',
                    'backgroundColor' => 'rgba(34,197,94,0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false
                ];
            }
        }
            $exportYearlyDatasets = [];

        foreach ($ingredients as $ingredient) {
                $data = [];

                for ($i = 2; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $qty = InventoryTransaction::where('ingredient_id', $ingredient->id)
                        ->whereIn('type', ['export', 'loss'])
                        ->whereYear('performed_at', $year)
                        ->sum('quantity_base');

                    $data[] = $qty;
                }

                if (array_sum($data) > 0) {
                    $exportYearlyDatasets[] = [
                        'label' => $ingredient->name,
                        'data' => $data,
                        'borderColor' => 'rgba(239,68,68,1)',
                        'backgroundColor' => 'rgba(239,68,68,0.1)',
                        'borderWidth' => 2,
                        'tension' => 0.3,
                        'fill' => false
                    ];
                }
            }
        return view('admin.inventory.index', compact(
            'transactions',
            'importMonthlyDatasets', 'monthLabels',
            'exportMonthlyDatasets',
            'importChart',
            'exportChart',
            'importYearlyDatasets', 'yearLabels',
            'exportYearlyDatasets'
        ));

    }
    
    
}
