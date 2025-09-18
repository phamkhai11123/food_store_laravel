<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function cart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return view('shop.cart.index', ['cartItems' => $cartItems, 'subtotal' => 0]);
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('shop.cart.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Hiển thị trang thanh toán
     */
    public function checkout()
    {
        // Debug thông tin người dùng
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        // Lấy user_id từ người dùng đã đăng nhập
        $user = Auth::user();
        $userId = $user->id;

        // Lấy giỏ hàng
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Định nghĩa phí vận chuyển
        // $shippingFee = 30000; // Phí vận chuyển cố định 30,000đ
        // $shippingFee = $subtotal > 0 && $subtotal < 500000 ? 30000 : 0;
        $shippingFee = 0;
        // Tính tổng tiền
        $total = $subtotal + $shippingFee;

        return view('shop.checkout.index', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }    /**
     * Đặt hàng
     */
    public function store(OrderRequest $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        $user = Auth::user();

        // Lấy giỏ hàng
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Định nghĩa phí vận chuyển
        $shippingFee = 0; // Phí vận chuyển cố định 30,000đ

        // Tính tổng tiền
        $total = $subtotal + $shippingFee;

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . time(),
                'total' => $total,
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'phone' => $request->phone_number,
                'address' => $request->shipping_address,
                'city' => $request->city ?? 'Không xác định',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'note' => $request->notes,
            ]);

            // Thêm các sản phẩm vào đơn hàng
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'product_name' => $item->product->name,
                ]);
            }

            // Xóa giỏ hàng
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Gửi email xác nhận đơn hàng (có thể thêm sau)

            return redirect()->route('shop.orders.show', $order->id)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        // Chỉ cho phép hủy đơn hàng ở trạng thái "đang xử lý"
        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Cập nhật trạng thái đơn hàng
            $order->status = 'cancelled';
            $order->save();

            // Log the status change
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'status' => 'cancelled',
                'comment' => 'Đơn hàng đã bị hủy bởi khách hàng',
                'data' => [
                    'old_status' => 'pending',
                    'new_status' => 'cancelled',
                ],
            ]);

            DB::commit();

            return back()->with('success', 'Đã hủy đơn hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi hủy đơn hàng. Vui lòng thử lại.');
        }
    }
    public function complete($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        // Chỉ cho phép xác nhận khi đơn hàng đang ở trạng thái "đang giao hàng"
        if ($order->status !== 'processing') {
            return back()->with('error', 'Không thể xác nhận đơn hàng ở trạng thái hiện tại.');
        }

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Cập nhật trạng thái đơn hàng
            $order->status = 'completed';
            $order->save();

            // Log the status change
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'status' => 'completed',
                'comment' => 'Đơn hàng đã được xác nhận giao thành công bởi khách hàng',
                'data' => [
                    'old_status' => 'processing',
                    'new_status' => 'completed',
                ],
            ]);

            DB::commit();

            return back()->with('success', 'Đã xác nhận nhận hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi xác nhận đơn hàng. Vui lòng thử lại.');
        }
    }
}
