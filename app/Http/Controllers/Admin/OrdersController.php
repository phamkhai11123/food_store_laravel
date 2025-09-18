<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Services\OrderProfitService;    
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    /**
     * Translate order status code to Vietnamese text
     */
    private function translateOrderStatus($status)
    {
        switch ($status) {
            case 'pending':
                return 'đang xử lý';
            case 'processing':
                return 'đang giao hàng';
            case 'completed':
                return 'đã giao hàng';
            case 'cancelled':
                return 'đã hủy';
            default:
                return $status;
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::query()->with(['user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status == '1');
        }

        // Filter by search term (order number, customer name, phone, email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Get total orders and amount for each status
        $statistics = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_amount' => Order::where('status', '!=', 'cancelled')->sum('profit'),
            'ingredient_cost_per_order'=>Order::where('status', '!=', 'cancelled')->sum('ingredient_cost'),
        ];

        // Sort orders
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        $orders = $query->paginate(5)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statistics' => $statistics,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'user']);

        // Get order history
        $orderHistory = OrderHistory::with('user')
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.orders.show', [
            'order' => $order,
            'orderHistory' => $orderHistory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['orderItems.product', 'user']);

        return view('admin.orders.edit', [
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|boolean',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'admin_comment' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'shipping_fee' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Update order details
            $oldStatus = $order->status;
            $oldPaymentStatus = $order->payment_status;

            $order->status = $validated['status'];
            $order->payment_status = $validated['payment_status'];
            $order->name = $validated['name'];
            $order->phone = $validated['phone'];
            $order->address = $validated['address'];
            $order->city = $validated['city'];
            $order->admin_comment = $validated['admin_comment'];
            $order->subtotal = $validated['subtotal'];
            $order->shipping_fee = $validated['shipping_fee'];
            $order->total = $validated['total'];

            $order->save();

            // Update order items
            foreach ($validated['items'] as $itemData) {
                $orderItem = $order->orderItems()->find($itemData['id']);
                if ($orderItem) {
                    $orderItem->price = $itemData['price'];
                    $orderItem->quantity = $itemData['quantity'];
                    $orderItem->save();
                }
            }

            // Log status change if it changed
            if ($oldStatus !== $validated['status']) {
                $oldStatusText = $this->translateOrderStatus($oldStatus);
                $newStatusText = $this->translateOrderStatus($validated['status']);

                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id ?? null,
                    'status' => $validated['status'],
                    'comment' => "Trạng thái đơn hàng đã được thay đổi từ {$oldStatusText} sang {$newStatusText}",
                    'data' => [
                        'old_status' => $oldStatus,
                        'new_status' => $validated['status'],
                    ],
                ]);
            }

            // Log payment status change if it changed
            if ($oldPaymentStatus != $validated['payment_status']) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id ?? null,
                    'status' => 'payment_status_changed',
                    'comment' => $validated['payment_status']
                        ? "Đơn hàng đã được đánh dấu là đã thanh toán"
                        : "Đơn hàng đã được đánh dấu là chờ xác nhận",
                    'data' => [
                        'payment_status' => $validated['payment_status'],
                    ],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Đơn hàng đã được cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $order->status = $validated['status'];
            $order->save();

            if ($order->status === 'completed' && $order->payment_status === '1') {
                $profitService = new \App\Services\OrderProfitService;
                // Eager load các quan hệ cần thiết để tính chi phí nguyên liệu
                $order->loadMissing('items.product.recipeItems.ingredient');

                // Tính toán và lưu chi phí nguyên liệu + lợi nhuận
                $order->ingredient_cost = $profitService->calculateIngredientCost($order);
                $order->profit = $profitService->calculateProfit($order);
                $order->save();
            }


            // Log the status change
            $oldStatusText = $this->translateOrderStatus($oldStatus);
            $newStatusText = $this->translateOrderStatus($validated['status']);

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id ?? null,
                'status' => $validated['status'],
                'comment' => "Trạng thái đơn hàng đã được thay đổi từ {$oldStatusText} sang {$newStatusText}",
                'data' => [
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                ],
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }


   
    /**
     * Mark the order as paid.
     */
    public function markPaid(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->payment_status = true;
            $order->save();

            // Log the payment status change
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id ?? null,
                'status' => 'payment_status_changed',
                'comment' => "Đơn hàng đã được đánh dấu là đã thanh toán",
                'data' => [
                    'payment_status' => true,
                ],
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Đơn hàng đã được đánh dấu là đã thanh toán');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark the order as unpaid.
     */
    public function markUnpaid(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->payment_status = false;
            $order->save();

            // Log the payment status change
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id ?? null,
                'status' => 'payment_status_changed',
                'comment' => "Đơn hàng đã được đánh dấu là chờ xác nhận",
                'data' => [
                    'payment_status' => false,
                ],
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Đơn hàng đã được đánh dấu là chờ xác nhận');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk update orders.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'action' => 'required|in:pending,processing,completed,cancelled,mark_paid,mark_unpaid',
        ]);

        try {
            DB::beginTransaction();

            $orderIds = $validated['order_ids'];
            $action = $validated['action'];
            $count = count($orderIds);

            switch ($action) {
                case 'pending':
                    // Get the original status for each order before updating
                    $orders = Order::whereIn('id', $orderIds)->get(['id', 'status'])->keyBy('id');

                    Order::whereIn('id', $orderIds)->update(['status' => 'pending']);

                    // Log history for each order
                    foreach ($orderIds as $orderId) {
                        if (isset($orders[$orderId])) {
                            $oldStatusText = $this->translateOrderStatus($orders[$orderId]->status);

                            OrderHistory::create([
                                'order_id' => $orderId,
                                'user_id' => auth()->id ?? null,
                                'status' => 'pending',
                                'comment' => "Trạng thái đơn hàng đã được thay đổi từ {$oldStatusText} sang đang xử lý (cập nhật hàng loạt)",
                                'data' => ['bulk_update' => true],
                            ]);
                        }
                    }

                    $message = "{$count} đơn hàng đã được đánh dấu là đang xử lý";
                    break;

                case 'processing':
                    // Get the original status for each order before updating
                    $orders = Order::whereIn('id', $orderIds)->get(['id', 'status'])->keyBy('id');

                    Order::whereIn('id', $orderIds)->update(['status' => 'processing']);

                    $processingOrders = Order::whereIn('id', $orderIds)->where('status', 'processing')->get();

                    try {
                        foreach ($processingOrders as $order) {
                            foreach ($order->items as $item) {
                                $product = $item->product;
                                $product->loadMissing('recipeItems.ingredient');

                                foreach ($product->recipeItems as $recipe) {
                                    $ingredient = $recipe->ingredient;

                                    if (!$ingredient) {
                                        Log::warning("Thiếu nguyên liệu cho recipe ID {$recipe->id}");
                                        continue;
                                    }

                                    $usedQty = $recipe->quantity_per_portion_base * $item->quantity;

                                    $ingredient->track_stock -= $usedQty;
                                    $ingredient->save();

                                    InventoryTransaction::create([
                                        'ingredient_id' => $ingredient->id,
                                        'type' => 'export',
                                        'quantity_base' => $usedQty,
                                        'performed_at' => now(),
                                        'note' => "Xuất kho khi xử lý đơn hàng #{$order->order_number}",
                                        'ref_id' => $order->id,
                                    ]);
                                    Log::info("→ Đã ghi nhận xuất kho '{$ingredient->name}' số lượng {$usedQty} cho đơn hàng #{$order->id}.");
                                }
                            }
                        }
                    } catch (\Throwable $e) {
                        Log::error("Lỗi khi trừ kho: " . $e->getMessage());
                    }

                    // Log history for each order
                    foreach ($orderIds as $orderId) {
                        if (isset($orders[$orderId])) {
                            $oldStatusText = $this->translateOrderStatus($orders[$orderId]->status);

                            OrderHistory::create([
                                'order_id' => $orderId,
                                'user_id' => auth()->id ?? null,
                                'status' => 'processing',
                                'comment' => "Trạng thái đơn hàng đã được thay đổi từ {$oldStatusText} sang đang giao hàng (cập nhật hàng loạt)",
                                'data' => ['bulk_update' => true],
                            ]);
                        }
                    }

                    $message = "{$count} đơn hàng đã được đánh dấu là đang giao hàng";
                    break;

                case 'completed':
                    // Get the original status for each order before updating
                    $orders = Order::whereIn('id', $orderIds)->get(['id', 'status'])->keyBy('id');

                    Order::whereIn('id', $orderIds)->update(['status' => 'completed']);

                    $completedOrders = Order::whereIn('id', $orderIds)->get();

                    $profitService = new OrderProfitService;

                    foreach ($completedOrders as $order) {
                        // Load đầy đủ quan hệ để tính chi phí
                        $order->loadMissing('items.product.recipeItems.ingredient');

                        // Tính toán chi phí nguyên liệu và lợi nhuận
                        $order->ingredient_cost = $profitService->calculateIngredientCost($order);
                        $order->profit = $profitService->calculateProfit($order);
                        $order->save(); // Ghi vào DB
                       
                    }

                    // Log history for each order
                    foreach ($orderIds as $orderId) {
                        if (isset($orders[$orderId])) {
                            $oldStatusText = $this->translateOrderStatus($orders[$orderId]->status);

                            OrderHistory::create([
                                'order_id' => $orderId,
                                'user_id' => auth()->id ?? null,
                                'status' => 'completed',
                                'comment' => "Trạng thái đơn hàng đã được thay đổi từ {$oldStatusText} sang đã giao hàng (cập nhật hàng loạt)",
                                'data' => ['bulk_update' => true],
                            ]);
                        }
                    }

                    $message = "{$count} đơn hàng đã được đánh dấu là đã giao hàng";
                    break;

                case 'cancelled':
                    // Get the original status for each order before updating
                    $orders = Order::whereIn('id', $orderIds)->get(['id', 'status'])->keyBy('id');

                    Order::whereIn('id', $orderIds)->update(['status' => 'cancelled']);

                    // Log history for each order
                    foreach ($orderIds as $orderId) {
                        if (isset($orders[$orderId])) {
                            $oldStatusText = $this->translateOrderStatus($orders[$orderId]->status);

                            OrderHistory::create([
                                'order_id' => $orderId,
                                'user_id' => auth()->id ?? null,
                                'status' => 'cancelled',
                                'comment' => "Trạng thái đơn hàng đã được thay đổi từ {$oldStatusText} sang đã hủy (cập nhật hàng loạt)",
                                'data' => ['bulk_update' => true],
                            ]);
                        }
                    }

                    $message = "{$count} đơn hàng đã bị hủy";
                    break;

                case 'mark_paid':
                    Order::whereIn('id', $orderIds)->update(['payment_status' => true]);

                    // Log history for each order
                    foreach ($orderIds as $orderId) {
                        OrderHistory::create([
                            'order_id' => $orderId,
                            'user_id' => auth()->id ?? null,
                            'status' => 'payment_status_changed',
                            'comment' => 'Đơn hàng đã được đánh dấu là đã thanh toán (bulk update)',
                            'data' => ['payment_status' => true, 'bulk_update' => true],
                        ]);
                    }

                    $message = "{$count} đơn hàng đã được đánh dấu là đã thanh toán";
                    break;

                case 'mark_unpaid':
                    Order::whereIn('id', $orderIds)->update(['payment_status' => false]);

                    // Log history for each order
                    foreach ($orderIds as $orderId) {
                        OrderHistory::create([
                            'order_id' => $orderId,
                            'user_id' => auth()->id ?? null,
                            'status' => 'payment_status_changed',
                            'comment' => 'Đơn hàng đã được đánh dấu là chờ xác nhận (bulk update)',
                            'data' => ['payment_status' => false, 'bulk_update' => true],
                        ]);
                    }

                    $message = "{$count} đơn hàng đã được đánh dấu là chờ xác nhận";
                    break;
            }

            DB::commit();

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Print the order.
     */
    public function print(Order $order)
    {
        $order->load(['orderItems.product', 'user']);

        return view('admin.orders.print', [
            'order' => $order,
        ]);
    }
}
