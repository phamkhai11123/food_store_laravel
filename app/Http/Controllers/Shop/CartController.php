<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.promotions')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->getDiscountedPrice();
        });

        // Calculate shipping fee (30,000 VND if subtotal is less than 500,000 VND, otherwise free)
        // $shippingFee = $subtotal > 0 && $subtotal < 500000 ? 30000 : 0;
        $shippingFee  = 0;  
        // Calculate total
        $total = $subtotal + $shippingFee;

        return view('shop.cart.index', compact(
            'cartItems',
            'subtotal',
            'shippingFee',
            'total'
        ));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $product = Product::findOrFail($request->product_id);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Cập nhật số lượng
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Thêm mới vào giỏ hàng
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $product = $cartItem->product;

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng.');
    }


}
