<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $transactions = InventoryTransaction::with('ingredients','order')
    //         ->orderByDesc('performed_at')
    //         ->paginate(50);
    //     return view('admin.inventory.index',compact('transactions'));
    // }
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

        return view('admin.inventory.index', compact('transactions'));
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
        //
    }
}
