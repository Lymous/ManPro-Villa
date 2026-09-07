<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\BookingAddOn;
use App\Models\BookingLog;
use App\Models\BookingRoom;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Policy;
use App\Models\RoomType;
use App\Services\TripayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    protected TripayService $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    /**
     * Homepage Bergaya Nginapsini.id
     */
    public function index()
    {
        $packages = RoomType::where('kategori', 'paket')
            ->where('is_active', true)
            ->with(['photos', 'primaryPhoto'])
            ->get();

        $featuredPackages = $packages->take(6);
        $addOns = AddOn::where('is_active', true)->get();

        $policies = [
            'dp_percentage' => Policy::get('dp_percentage', 50),
            'villa_contact_wa' => Policy::get('villa_contact_wa', '085808408835'),
            'villa_address' => Policy::get('villa_address', 'Jln. Air Panas KM. 2, Gapuk, Made, Pacet, Mojokerto'),
        ];

        return view('pages.home', compact('packages', 'featuredPackages', 'addOns', 'policies'));
    }

    /**
     * Daftar Semua Paket & Kamar
     */
    public function packages(Request $request)
    {
        $query = RoomType::where('is_active', true)->with(['photos', 'primaryPhoto']);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tamu')) {
            $guests = (int) $request->tamu;
            $query->where('kapasitas_max', '>=', $guests);
        }

        if ($request->filled('min_price') && (float) $request->min_price > 0) {
            $query->where('harga_dasar', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price') && (float) $request->max_price > 0) {
            $query->where('harga_dasar', '<=', (float) $request->max_price);
        }

        if ($request->filled('min_kamar') && (int) $request->min_kamar > 0) {
            $query->where('jumlah_unit', '>=', (int) $request->min_kamar);
        }

        if ($request->filled('lokasi') && $request->lokasi !== '') {
            $lokasiKeyword = $request->lokasi === 'gapuk' ? 'Gapuk' : 'Pacet';
            $query->where(function ($q) use ($lokasiKeyword) {
                $q->where('deskripsi', 'like', '%'.$lokasiKeyword.'%')
                    ->orWhere('fasilitas', 'like', '%'.$lokasiKeyword.'%')
                    ->orWhere('nama', 'like', '%'.$lokasiKeyword.'%');
            });
        }

        if ($request->filled('fasilitas') && $request->fasilitas !== 'semua') {
            $fasilitasKeyword = $request->fasilitas;
            $query->where(function ($q) use ($fasilitasKeyword) {
                $q->where('fasilitas', 'like', '%'.$fasilitasKeyword.'%')
                    ->orWhere('deskripsi', 'like', '%'.$fasilitasKeyword.'%')
                    ->orWhere('nama', 'like', '%'.$fasilitasKeyword.'%');
            });
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'harga_rendah' => $query->orderBy('harga_dasar', 'asc'),
                'harga_tinggi' => $query->orderBy('harga_dasar', 'desc'),
                'kapasitas' => $query->orderBy('kapasitas_max', 'desc'),
                default => null,
            };
        }

        $packages = $query->get();

        return view('pages.packages', compact('packages'));
    }

    /**
     * Detail Paket Villa
     */
    public function packageDetail($slug)
    {
        $package = RoomType::where('slug', $slug)
            ->with(['photos'])
            ->firstOrFail();

        $addOns = AddOn::where('is_active', true)->get();
        $relatedPackages = RoomType::where('id', '!=', $package->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        $dpPercentage = (int) Policy::get('dp_percentage', 50);

        return view('pages.package-detail', compact('package', 'addOns', 'relatedPackages', 'dpPercentage'));
    }

    /**
     * Halaman Checkout Reservasi
     */
    public function checkout(Request $request, $slug)
    {
        $package = RoomType::where('slug', $slug)->firstOrFail();

        $checkIn = $request->input('check_in', now()->addDay()->format('Y-m-d'));
        $checkOut = $request->input('check_out', now()->addDays(2)->format('Y-m-d'));
        $guests = (int) $request->input('guests', $package->kapasitas_min);

        $cIn = Carbon::parse($checkIn);
        $cOut = Carbon::parse($checkOut);
        $nights = max(1, $cIn->diffInDays($cOut));

        $basePrice = $package->harga_dasar * $nights;
        $dpPercentage = (int) Policy::get('dp_percentage', 50);
        $dpAmount = ($basePrice * $dpPercentage) / 100;

        $addOns = AddOn::where('is_active', true)->get();
        $paymentChannels = $this->tripayService->getPaymentChannels();

        return view('pages.checkout', compact(
            'package',
            'checkIn',
            'checkOut',
            'guests',
            'nights',
            'basePrice',
            'dpPercentage',
            'dpAmount',
            'addOns',
            'paymentChannels'
        ));
    }

    /**
     * Menyimpan Pesanan & Menginisiasi Pembayaran
     */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'nama_tamu' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'jumlah_tamu' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string', // Tripay channel code or 'manual_transfer'
            'skema_bayar' => 'required|in:dp,lunas',
        ]);

        $roomType = RoomType::findOrFail($request->room_type_id);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        // Cek apakah tanggal diblokir
        $isBlocked = BlockedDate::where(function ($q) use ($roomType) {
            $q->whereNull('room_type_id')->orWhere('room_type_id', $roomType->id);
        })
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('tanggal_mulai', [$checkIn, $checkOut])
                    ->orWhereBetween('tanggal_selesai', [$checkIn, $checkOut])
                    ->orWhere(function ($sub) use ($checkIn, $checkOut) {
                        $sub->where('tanggal_mulai', '<=', $checkIn)
                            ->where('tanggal_selesai', '>=', $checkOut);
                    });
            })->exists();

        if ($isBlocked) {
            return back()->withInput()->with('error', 'Maaf, tanggal yang dipilih sedang dalam jadwal pemeliharaan internal atau tidak tersedia.');
        }

        // Kalkulasi biaya
        $roomPricePerNight = $roomType->harga_dasar;
        $roomSubtotal = $roomPricePerNight * $nights;
        $addOnSubtotal = 0;

        $selectedAddOns = $request->input('addons', []); // [add_on_id => qty]

        $addOnItems = [];
        if (is_array($selectedAddOns)) {
            foreach ($selectedAddOns as $addonId => $qty) {
                $qty = (int) $qty;
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

        // DP %
        $dpPercentage = (int) Policy::get('dp_percentage', 50);
        $nominalDp = ($totalHarga * $dpPercentage) / 100;

        $nominalBayar = ($request->skema_bayar === 'dp') ? $nominalDp : $totalHarga;

        // Kode unik untuk transfer manual jika dipilih
        $kodeUnik = ($request->metode_pembayaran === 'manual_transfer') ? rand(100, 999) : 0;
        $nominalTransfer = $nominalBayar + $kodeUnik;

        // Durasi hold
        $holdHours = (int) Policy::get('hold_duration_hours', 24);
        $holdExpiresAt = now()->addHours($holdHours);

        // Generate Kode Booking
        $dateStr = now()->format('Ymd');
        $randomSeq = strtoupper(Str::random(4));
        $kodeBooking = "VBB-{$dateStr}-{$randomSeq}";

        // 1. Buat Record Booking
        $booking = Booking::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'kode_booking' => $kodeBooking,
            'created_by' => null, // Dibuat tamu lewat web
            'nama_tamu' => $request->nama_tamu,
            'no_wa' => $request->no_wa,
            'email' => $request->email,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'jumlah_tamu' => $request->jumlah_tamu,
            'sumber' => 'website',
            'total_harga' => $totalHarga,
            'nominal_dp' => $nominalDp,
            'kode_unik' => $kodeUnik,
            'nominal_transfer' => $nominalTransfer,
            'status' => 'menunggu_pembayaran',
            'hold_expires_at' => $holdExpiresAt,
            'catatan' => $request->catatan,
        ]);

        // 2. Buat BookingRoom
        BookingRoom::create([
            'booking_id' => $booking->id,
            'room_type_id' => $roomType->id,
            'room_id' => null, // Ditugaskan admin saat check-in
            'jumlah_kamar' => 1,
            'harga_per_malam' => $roomPricePerNight,
            'jumlah_malam' => $nights,
            'subtotal' => $roomSubtotal,
        ]);

        // 3. Buat BookingAddOns
        foreach ($addOnItems as $item) {
            BookingAddOn::create([
                'booking_id' => $booking->id,
                'add_on_id' => $item['add_on']->id,
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // 4. Catat Log
        BookingLog::log($booking->id, 'create_booking', null, 'menunggu_pembayaran');

        // 5. Buat Record Payment
        $paymentType = ($request->skema_bayar === 'dp') ? 'dp' : 'pembayaran_penuh';
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'verified_by' => null,
            'jenis' => $paymentType,
            'metode_pembayaran' => $request->metode_pembayaran,
            'nominal' => $nominalTransfer,
            'status' => 'menunggu_konfirmasi',
        ]);

        // 6. Jika metode Tripay, request closed payment ke TripayService
        if ($request->metode_pembayaran !== 'manual_transfer') {
            $this->tripayService->createClosedTransaction($booking, $payment, $request->metode_pembayaran);
        }

        // 7. Notifikasi Awal
        $waMsg = "Halo {$booking->nama_tamu}, terima kasih telah melakukan pemesanan di Villa Bukit Bunga Pacet. Kode Reservasi Anda: {$booking->kode_booking}. Total tagihan: Rp ".number_format($nominalTransfer, 0, ',', '.').'. Silakan selesaikan pembayaran sebelum batas hold: '.$holdExpiresAt->format('d M Y H:i').' WIB.';
        Notification::create([
            'booking_id' => $booking->id,
            'jenis' => 'wa_konfirmasi',
            'no_tujuan' => $booking->no_wa,
            'pesan' => $waMsg,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('booking.invoice', $booking->kode_booking)
            ->with('success', 'Pemesanan Anda berhasil dibuat! Silakan selesaikan pembayaran berikut.');
    }

    /**
     * Halaman Invoice & Instruksi Pembayaran Tripay / Manual
     */
    public function invoice($kodeBooking)
    {
        $booking = Booking::where('kode_booking', $kodeBooking)
            ->with(['bookingRooms.roomType', 'bookingAddOns.addOn', 'payments'])
            ->firstOrFail();

        $latestPayment = $booking->payments()->latest()->first();

        $manualBank = [
            'name' => Policy::get('manual_bank_name', 'BCA'),
            'account' => Policy::get('manual_bank_account', '0182736455'),
            'holder' => Policy::get('manual_bank_holder', 'Villa Bukit Bunga Pacet'),
        ];

        $villaWa = Policy::get('villa_contact_wa', '085808408835');

        return view('pages.invoice', compact('booking', 'latestPayment', 'manualBank', 'villaWa'));
    }

    /**
     * Upload Bukti Transfer Manual
     */
    public function uploadManualProof(Request $request, $kodeBooking)
    {
        $booking = Booking::where('kode_booking', $kodeBooking)->firstOrFail();

        $request->validate([
            'file_bukti' => 'required|image|mimes:jpeg,png,jpg|max:3072',
            'tanggal_transfer' => 'required|date',
        ]);

        $path = $request->file('file_bukti')->store('proofs', 'public');

        $payment = $booking->payments()->latest()->first();
        if ($payment) {
            $payment->update([
                'file_bukti' => '/storage/'.$path,
                'tanggal_transfer' => $request->tanggal_transfer,
                'status' => 'menunggu_konfirmasi',
            ]);
        }

        BookingLog::log($booking->id, 'upload_transfer_proof', null, 'menunggu_verifikasi_admin');

        return back()->with('success', 'Bukti transfer berhasil diunggah! Admin kami akan segera memverifikasi pesanan Anda.');
    }

    /**
     * Halaman Pelacak Reservasi (Track Booking)
     */
    public function trackBooking(Request $request)
    {
        $booking = null;
        if ($request->filled('kode_booking')) {
            $booking = Booking::where('kode_booking', trim($request->kode_booking))
                ->with(['bookingRooms.roomType', 'bookingAddOns.addOn', 'payments'])
                ->first();
        }

        return view('pages.track', compact('booking'));
    }
}
