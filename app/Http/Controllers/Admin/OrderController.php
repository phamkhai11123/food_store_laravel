<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Tìm kiếm theo mã đơn hàng
        if ($request->has('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        // Lọc theo thời gian
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, Order $order,InventoryTransaction $inventory)
    {
        
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Cập nhật trạng thái đơn hàng
            $order->status = $newStatus;
            $order->save();


            // // Nếu đơn hàng bị hủy, hoàn lại số lượng tồn kho
            // if ($oldStatus != 'cancelled' && $newStatus == 'cancelled') {
            //     foreach ($order->orderItems as $item) {
            //         $product = $item->product;
            //         $product->stock += $item->quantity;
            //         $product->save();
            //     }
            // }

            // // Nếu đơn hàng từ hủy sang trạng thái khác, trừ lại số lượng tồn kho
            // if ($oldStatus == 'cancelled' && $newStatus != 'cancelled') {
            //     foreach ($order->orderItems as $item) {
            //         $product = $item->product;

            //         // Kiểm tra xem còn đủ số lượng tồn kho không
            //         if ($product->stock < $item->quantity) {
            //             throw new \Exception("Sản phẩm {$product->name} không đủ số lượng tồn kho.");
            //         }

            //         $product->stock -= $item->quantity;
            //         $product->save();
            //     }
            // }
            $isPostingTransition = ($oldStatus !== 'cancelled') && ($newStatus === 'processing');
            $isUnpostingTransition = ($oldStatus !== 'cancelled') && ($newStatus === 'cancelled');

            if ($isPostingTransition) {

                $order->load(['orderItems.menuItem.recipeItems']);
                $inventory->ensureSufficientStockOrFail($order);

                $inventory->postOrder($order);
            }

            if ($isUnpostingTransition) {
                $inventory->unpostOrder($order);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
