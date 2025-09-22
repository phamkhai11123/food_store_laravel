<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSeat;
use App\Models\TableReservation;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = TableReservation::latest()->paginate(30);
        return view('admin.reservations.index', compact('reservations'));
    }

     public function updateStatus(Request $request, $id)
    {
        $reservation = TableReservation::findOrFail($id);
        $newStatus = $request->status;

        $seat = RestaurantSeat::first();

        if ($newStatus === 'approved') {
            if ($seat->available_seats >= $reservation->guest_count) {
                $reservation->update(['status' => 'approved']);
                $seat->decrement('available_seats', $reservation->guest_count);
            } else {
                return back()->with('error', 'Không đủ ghế trống!');
            }
        }

        if (in_array($newStatus, ['cancelled', 'done']) && $reservation->status === 'approved') {
            $seat->increment('available_seats', $reservation->guest_count);
            $reservation->update(['status' => $newStatus]);
        }

        return back()->with('success', 'Cập nhật trạng thái thành công');
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
