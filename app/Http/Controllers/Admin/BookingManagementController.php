<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\BookingAddOn;
use App\Models\BookingLog;
use App\Models\BookingRoom;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Policy;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['bookingRooms.roomType', 'bookingRooms.room', 'payments'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_booking', 'like', "%{$s}%")
                  ->orWhere('nama_tamu', 'like', "%{$s}%")
                  ->orWhere('no_wa', 'like', "%{$s}%");
            });
        }

        if ($request->filled('check_in')) {
            $query->whereDate('check_in', $request->check_in);
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'creator',
            'bookingRooms.roomType',
            'bookingRooms.room',
            'bookingAddOns.addOn',
            'payments.verifier',
            'bookingLogs.user',
            'notifications',
        ])->findOrFail($id);

        $availableRooms = Room::where('status', 'tersedia')
            ->orWhereIn('id', $booking->bookingRooms->pluck('room_id')->filter())
            ->with('roomType')
            ->get();

        return view('admin.bookings.show', compact('booking', 'availableRooms'));
    }

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->get();
        $addOns = AddOn::where('is_active', true)->get();

        return view('admin.bookings.create', compact('roomTypes', 'addOns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'nama_tamu' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'jumlah_tamu' => 'required|integer|min:1',
            'sumber' => 'required|in:whatsapp,instagram,offline,website',
            'status' => 'required|in:menunggu_pembayaran,dp_dibayar,lunas,check_in',
        ]);

        $roomType = RoomType::findOrFail($request->room_type_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = max(1, $checkIn->diffInDays($checkOut));

        $roomSubtotal = $roomType->harga_dasar * $nights;
        $addOnSubtotal = 0;

        $selectedAddOns = $request->input('addons', []);
        $addOnItems = [];
        if (is_array($selectedAddOns)) {
            foreach ($selectedAddOns as $addonId => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $addon = AddOn::find($addonId);
                    if ($addon) {
                        $sub = $addon->harga * $qty;
                        $addOnSubtotal += $sub;
                        $addOnItems[] = [
                            'add_on' => $addon,
                            'jumlah' => $qty,
                            'harga_satuan' => $addon->harga,
                            'subtotal' => $sub,
                        ];
                    }
                }
            }
        }

        $totalHarga = $roomSubtotal + $addOnSubtotal;
        $dpPercentage = (int) Policy::get('dp_percentage', 50);
        $nominalDp = ($totalHarga * $dpPercentage) / 100;

        $dateStr = now()->format('Ymd');
        $randomSeq = strtoupper(Str::random(4));
        $kodeBooking = "VBB-{$dateStr}-{$randomSeq}";

        $booking = Booking::create([
            'kode_booking' => $kodeBooking,
            'created_by' => auth()->id(), // Tercatat admin yang menginput
            'nama_tamu' => $request->nama_tamu,
            'no_wa' => $request->no_wa,
            'email' => $request->email,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'jumlah_tamu' => $request->jumlah_tamu,
            'sumber' => $request->sumber,
            'total_harga' => $totalHarga,
            'nominal_dp' => $nominalDp,
            'kode_unik' => 0,
            'nominal_transfer' => $totalHarga,
            'status' => $request->status,
            'hold_expires_at' => now()->addHours(24),
            'catatan' => $request->catatan,
        ]);

        BookingRoom::create([
            'booking_id' => $booking->id,
            'room_type_id' => $roomType->id,
            'room_id' => null,
            'jumlah_kamar' => 1,
            'harga_per_malam' => $roomType->harga_dasar,
            'jumlah_malam' => $nights,
            'subtotal' => $roomSubtotal,
        ]);

        foreach ($addOnItems as $item) {
            BookingAddOn::create([
                'booking_id' => $booking->id,
                'add_on_id' => $item['add_on']->id,
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Jika status yang dipilih langsung DP atau Lunas, buat record payment
        if (in_array($request->status, ['dp_dibayar', 'lunas'])) {
            $nominalPaid = ($request->status === 'dp_dibayar') ? $nominalDp : $totalHarga;
            Payment::create([
                'booking_id' => $booking->id,
                'verified_by' => auth()->id(),
                'jenis' => ($request->status === 'dp_dibayar') ? 'dp' : 'pembayaran_penuh',
                'metode_pembayaran' => 'manual_admin',
                'nominal' => $nominalPaid,
                'status' => 'diverifikasi',
                'verified_at' => now(),
            ]);
        }

        BookingLog::log(
            $booking->id,
            'manual_create_by_admin',
            null,
            "Dibuat manual oleh " . auth()->user()->name . " (Sumber: {$request->sumber})"
        );

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Reservasi manual berhasil dibuat dengan kode: ' . $booking->kode_booking);
    }

    /**
     * Penetapan Kamar Fisik (Room Assignment saat Check-in)
     */
    public function assignRoom(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate([
            'booking_room_id' => 'required|exists:booking_rooms,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $bookingRoom = BookingRoom::where('booking_id', $booking->id)
            ->findOrFail($request->booking_room_id);

        $oldRoom = $bookingRoom->room?->nomor_kamar;
        if ($bookingRoom->room_id) {
            Room::where('id', $bookingRoom->room_id)->update(['status' => 'tersedia']);
        }

        $newRoom = Room::findOrFail($request->room_id);
        $bookingRoom->update(['room_id' => $newRoom->id]);
        $newRoom->update(['status' => 'terisi']);

        BookingLog::log(
            $booking->id,
            'assign_room',
            $oldRoom ? "Kamar {$oldRoom}" : 'Belum ditetapkan',
            "Ditetapkan ke Kamar {$newRoom->nomor_kamar}"
        );

        return back()->with('success', "Unit kamar fisik berhasil ditetapkan ke: {$newRoom->nomor_kamar}");
    }

    /**
     * Update Status Pemesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,dp_dibayar,lunas,check_in,selesai,batal',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update(['status' => $newStatus]);

        // Jika selesai / batal, bebaskan status kamar fisik
        if (in_array($newStatus, ['selesai', 'batal'])) {
            foreach ($booking->bookingRooms as $br) {
                if ($br->room_id) {
                    Room::where('id', $br->room_id)->update(['status' => 'tersedia']);
                }
            }
        }

        // Jika check in, pastikan kamar terisi
        if ($newStatus === 'check_in') {
            foreach ($booking->bookingRooms as $br) {
                if ($br->room_id) {
                    Room::where('id', $br->room_id)->update(['status' => 'terisi']);
                }
            }
        }

        BookingLog::log(
            $booking->id,
            'change_status',
            $oldStatus,
            $newStatus
        );

        return back()->with('success', "Status reservasi berhasil diubah menjadi: " . $booking->status_label);
    }

    /**
     * Reschedule Jadwal Menginap
     */
    public function reschedule(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'new_check_in' => 'required|date|after_or_equal:today',
            'new_check_out' => 'required|date|after:new_check_in',
            'alasan' => 'nullable|string',
        ]);

        $maxReschedule = (int) Policy::get('max_reschedule_count', 1);
        if ($booking->jumlah_reschedule >= $maxReschedule) {
            return back()->with('error', "Batas maksimal penjadwalan ulang ({$maxReschedule}x) telah tercapai.");
        }

        $oldDates = "{$booking->check_in->format('d/m/Y')} s/d {$booking->check_out->format('d/m/Y')}";
        $newDates = Carbon::parse($request->new_check_in)->format('d/m/Y') . " s/d " . Carbon::parse($request->new_check_out)->format('d/m/Y');

        $booking->update([
            'check_in' => $request->new_check_in,
            'check_out' => $request->new_check_out,
            'jumlah_reschedule' => $booking->jumlah_reschedule + 1,
            'status' => 'reschedule',
        ]);

        BookingLog::log(
            $booking->id,
            'reschedule_dates',
            $oldDates,
            "Diubah ke {$newDates}. Alasan: " . ($request->alasan ?? 'Permintaan Tamu')
        );

        // Notifikasi WA
        Notification::create([
            'booking_id' => $booking->id,
            'jenis' => 'wa_reschedule',
            'no_tujuan' => $booking->no_wa,
            'pesan' => "Halo {$booking->nama_tamu}, jadwal menginap Anda di Villa Bukit Bunga ({$booking->kode_booking}) telah berhasil diubah ke tanggal {$newDates}. Sampai jumpa!",
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', "Penjadwalan ulang berhasil diterapkan untuk tanggal: {$newDates}");
    }
}
