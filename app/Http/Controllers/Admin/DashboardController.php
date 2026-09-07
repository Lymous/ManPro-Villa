<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        // Operasional Hari Ini
        $checkInsToday = Booking::where('check_in', $today)
            ->whereIn('status', ['dp_dibayar', 'lunas', 'check_in'])
            ->with(['bookingRooms.roomType', 'bookingRooms.room'])
            ->get();

        $checkOutsToday = Booking::where('check_out', $today)
            ->whereIn('status', ['check_in', 'lunas'])
            ->with(['bookingRooms.roomType', 'bookingRooms.room'])
            ->get();

        $activeGuestsCount = Booking::where('status', 'check_in')->count();
        $pendingPaymentsCount = Booking::where('status', 'menunggu_pembayaran')->count();

        // Finansial (Hanya untuk Admin)
        $totalRevenue = 0;
        $monthlyRevenue = 0;
        if (auth()->user()->isAdmin()) {
            $totalRevenue = Payment::where('status', 'diverifikasi')->sum('nominal');
            $monthlyRevenue = Payment::where('status', 'diverifikasi')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('nominal');
        }

        // Statistik Kamar Fisik
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'terisi')->count();
        $maintenanceRooms = Room::where('status', 'pemeliharaan')->count();
        $availableRooms = Room::where('status', 'tersedia')->count();

        // 5 Booking Terkini
        $recentBookings = Booking::with(['bookingRooms.roomType'])
            ->latest()
            ->take(6)
            ->get();

        // Data Kalender Reservasi 30 Hari ke Depan
        $calendarEvents = [];
        $activeBookings = Booking::whereIn('status', ['dp_dibayar', 'lunas', 'check_in'])
            ->where('check_out', '>=', now()->subDays(5)->toDateString())
            ->with(['bookingRooms.roomType'])
            ->get();

        foreach ($activeBookings as $b) {
            $firstRoom = $b->bookingRooms->first()?->roomType?->nama ?? 'Paket Villa';
            $calendarEvents[] = [
                'title' => "{$b->kode_booking} - {$b->nama_tamu} ({$firstRoom})",
                'start' => $b->check_in->format('Y-m-d'),
                'end' => $b->check_out->format('Y-m-d'),
                'url' => route('admin.bookings.show', $b->id),
                'color' => $b->status === 'lunas' ? '#10B981' : ($b->status === 'check_in' ? '#6366F1' : '#3B82F6'),
            ];
        }

        // Tanggal yang diblokir
        $blockedDates = BlockedDate::with('roomType')->get();
        foreach ($blockedDates as $bd) {
            $calendarEvents[] = [
                'title' => 'BLOKIR: '.($bd->roomType?->nama ?? 'Seluruh Villa').' ('.ucfirst($bd->alasan).')',
                'start' => $bd->tanggal_mulai->format('Y-m-d'),
                'end' => $bd->tanggal_selesai->addDay()->format('Y-m-d'),
                'color' => '#EF4444',
            ];
        }

        return view('admin.dashboard', compact(
            'checkInsToday',
            'checkOutsToday',
            'activeGuestsCount',
            'pendingPaymentsCount',
            'totalRevenue',
            'monthlyRevenue',
            'totalRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'availableRooms',
            'recentBookings',
            'calendarEvents'
        ));
    }
}
