<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shop.reservation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validate dữ liệu đầu vào
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'arrival_date' => 'required|date',
            'arrival_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:20',
            'note' => 'nullable|string',
        ]);

        // ✅ Gộp ngày và giờ thành datetime
        $arrival = \Carbon\Carbon::parse($request->arrival_date . ' ' . $request->arrival_time);

        // ✅ Kiểm tra thời gian có hợp lệ không
        if ($arrival->isPast()) {
            return back()->with('error', 'Thời gian đến phải là thời gian trong tương lai.');
        }

        // ✅ Kiểm tra số ghế còn lại
        $seat = \App\Models\RestaurantSeat::first();

        if (!$seat) {
            return back()->with('error', 'Hệ thống chưa được khởi tạo số ghế. Vui lòng liên hệ quản trị viên.');
        }

        if ($seat->available_seats < $request->guest_count) {
            return back()->with('error', 'Hiện tại không đủ ghế trống để đặt bàn. Vui lòng thử lại sau hoặc liên hệ chủ quán.');
        }

        // ✅ Tạo đơn đặt bàn
        TableReservation::create([
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'arrival_time' => $arrival,
            'guest_count' => $request->guest_count,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('reservation.create')->with('success', '✅ Đặt bàn thành công! Vui lòng liên hệ chủ quán: 0988.123.456');
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
