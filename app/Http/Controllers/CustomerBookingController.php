<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerBookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bookings = $user->customerBookings()
            ->with(['bookingRooms.roomType', 'bookingRooms.room', 'payments'])
            ->latest()
            ->paginate(10);

        $activeBookingsCount = $user->customerBookings()
            ->whereIn('status', ['menunggu_pembayaran', 'dp_dibayar', 'lunas', 'check_in'])
            ->count();

        $completedBookingsCount = $user->customerBookings()
            ->where('status', 'selesai')
            ->count();

        return view('customer.bookings.index', compact('bookings', 'activeBookingsCount', 'completedBookingsCount'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profil dan nomor WhatsApp Anda berhasil diperbarui.');
    }
}
