<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ReviewRequest;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Hiển thị form đánh giá sản phẩm
     */
    public function create($orderItemId)
    {
        $orderItem = OrderItem::with(['order', 'product', 'review'])
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id())->where('status', 'completed');
            })
            ->findOrFail($orderItemId);

        return view('shop.reviews.create', compact('orderItem'));
    }

    /**
     * Lưu đánh giá sản phẩm
     */
    public function store(ReviewRequest $request, $orderItemId)
    {
        $orderItem = OrderItem::with(['order', 'product'])
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id())->where('status', 'completed');
            })
            ->findOrFail($orderItemId);

        // Kiểm tra xem sản phẩm này đã được đánh giá chưa
        $existingReview = Review::where('user_id', Auth::id())
            ->where('order_item_id', $orderItem->id)
            ->first();

        if ($existingReview) {
            // Cập nhật đánh giá hiện có
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return redirect()->route('shop.orders.show', $orderItem->order_id)
                ->with('success', 'Cập nhật đánh giá thành công!');
        }

        // Tạo đánh giá mới
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('shop.orders.show', $orderItem->order_id)
            ->with('success', 'Đánh giá sản phẩm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa đánh giá
     */
    public function edit($orderItemId)
    {
        $orderItem = OrderItem::with(['order', 'product', 'review'])
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id())->where('status', 'completed');
            })
            ->findOrFail($orderItemId);

        if (!$orderItem->review) {
            return redirect()->route('shop.reviews.create', $orderItem->id);
        }

        return view('shop.reviews.edit', compact('orderItem'));
    }

    /**
     * Cập nhật đánh giá
     */
    public function update(ReviewRequest $request, $orderItemId)
    {
        $orderItem = OrderItem::with(['order', 'review'])
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id())->where('status', 'completed');
            })
            ->findOrFail($orderItemId);

        if (!$orderItem->review) {
            return redirect()->route('shop.reviews.create', $orderItem->id);
        }

        $orderItem->review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('shop.orders.show', $orderItem->order_id)
            ->with('success', 'Cập nhật đánh giá thành công!');
    }
}
