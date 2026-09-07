@extends('layouts.app')

@section('title', 'Invoice Reservasi ' . $booking->kode_booking . ' - Villa Bukit Bunga')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Status Banner -->
    <div class="bg-white rounded-3xl p-5 sm:p-8 border border-gray-200/80 shadow-xl mb-8 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-6 mb-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1">Kode Reservasi Anda</span>
                <h1 class="font-display text-xl sm:text-3xl font-bold text-primary flex items-center gap-2 sm:gap-3 flex-wrap">
                    <span>{{ $booking->kode_booking }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ $booking->kode_booking }}'); alert('Kode booking disalin!');" 
                            class="text-accent hover:text-accent-dark text-base" title="Salin Kode Booking">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </h1>
                <p class="text-xs text-gray-500 mt-1">Dibuat pada: {{ $booking->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-xs text-gray-400 block mb-1">Status Reservasi</span>
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold border {{ $booking->status_badge_class }}">
                    <i class="fa-solid fa-circle text-[8px]"></i>
                    {{ $booking->status_label }}
                </span>
            </div>
        </div>

        <!-- Timer Masa Hold (Jika Menunggu Pembayaran) -->
        @if($booking->status === 'menunggu_pembayaran')
        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shrink-0">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs sm:text-sm text-amber-900">Batas Waktu Pembayaran (Masa Tahan 24 Jam)</h4>
                    <p class="text-[11px] sm:text-xs text-amber-700">Selesaikan sebelum <span class="font-bold">{{ $booking->hold_expires_at ? $booking->hold_expires_at->format('d M Y, H:i') : '-' }} WIB</span> agar tanggal tidak dibatalkan otomatis.</p>
                </div>
            </div>

            <div class="text-center sm:text-right shrink-0 bg-white/80 px-4 py-2 rounded-xl border border-amber-200">
                <span class="text-[10px] text-amber-800 font-bold uppercase tracking-wider block">Sisa Waktu</span>
                <span class="font-mono text-lg font-bold text-amber-900" id="countdown-timer">--:--:--</span>
            </div>
        </div>
        @endif

        <!-- Informasi Detail Pembayaran / Rekening -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <div class="md:col-span-6 space-y-4">
                <h3 class="font-bold text-sm text-gray-800 uppercase tracking-wider">Instruksi Pembayaran</h3>

                @if($latestPayment && str_contains(strtolower($latestPayment->metode_pembayaran), 'qris'))
                    <!-- Tampilan QRIS -->
                    <div class="bg-gray-50 p-4 sm:p-6 rounded-2xl border border-gray-200 text-center">
                        <span class="text-xs font-bold text-gray-600 block mb-3">Scan QRIS via Dompet Digital / Mobile Banking</span>
                        <div class="w-48 h-48 sm:w-56 sm:h-56 mx-auto bg-white p-3 rounded-2xl border border-gray-200 shadow-sm mb-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($latestPayment->tripay_pay_code ?? 'QRIS-VILLA-BUKIT-BUNGA') }}" 
                                 alt="QRIS Pembayaran" class="w-full h-full object-contain">
                        </div>
                        <p class="text-[11px] text-gray-500">Mendukung: BCA, Mandiri, BRI, BNI, GoPay, ShopeePay, OVO, DANA, LinkAja</p>
                    </div>
                @elseif($latestPayment && $latestPayment->tripay_pay_code)
                    <!-- Tampilan Virtual Account / Retail Code -->
                    <div class="bg-gray-50 p-4 sm:p-6 rounded-2xl border border-gray-200">
                        <span class="text-xs text-gray-500 block mb-1">Saluran: <strong class="text-primary">{{ $latestPayment->tripay_channel ?? 'Virtual Account' }}</strong></span>
                        <span class="text-[11px] text-gray-400 block mb-2">Nomor Pembayaran / Kode VA:</span>
                        <div class="flex items-center justify-between gap-2 bg-white px-3 sm:px-4 py-3 rounded-xl border border-gray-200 font-mono text-base sm:text-lg font-bold text-primary">
                            <span class="break-all">{{ $latestPayment->tripay_pay_code }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ $latestPayment->tripay_pay_code }}'); alert('Nomor VA disalin!');" 
                                    class="text-accent hover:text-accent-dark text-sm shrink-0" title="Salin Kode">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Transfer Rekening Manual -->
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 space-y-3">
                        <span class="text-xs font-bold text-gray-700 block">Transfer Rekening Resmi:</span>
                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                            <p class="text-xs text-gray-500">{{ $manualBank['name'] }}</p>
                            <p class="font-mono text-lg font-bold text-primary">{{ $manualBank['account'] }}</p>
                            <p class="text-xs text-gray-700 font-medium">a.n {{ $manualBank['holder'] }}</p>
                        </div>
                        <p class="text-[11px] text-gray-500">Transfer tepat hingga 3 digit terakhir (kode unik) untuk kemudahan verifikasi otomatis.</p>
                    </div>
                @endif

                <!-- Total Tagihan Box -->
                <div class="bg-primary/5 p-4 rounded-2xl border border-primary/10">
                    <span class="text-xs text-gray-500 block">Nominal Tagihan Saat Ini:</span>
                    <span class="font-display text-2xl font-bold text-primary">
                        Rp {{ number_format($latestPayment ? $latestPayment->nominal : $booking->nominal_transfer, 0, ',', '.') }}
                    </span>
                    <span class="text-[11px] text-gray-500 block mt-0.5">
                        ({{ $latestPayment && $latestPayment->jenis === 'dp' ? 'Uang Muka DP 50%' : 'Pelunasan Penuh' }})
                    </span>
                </div>

                <!-- Sandbox Instant Simulation Button (Testing Mode - Hanya Staf & Lingkungan Lokal) -->
                @if(app()->environment('local') && auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isPenjaga()) && $booking->status === 'menunggu_pembayaran' && $latestPayment)
                <div class="pt-2">
                    <a href="{{ route('tripay.simulate_paid', $latestPayment->tripay_reference ?? $latestPayment->id) }}" 
                       class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition-colors">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>[Staff Testing] Simulasi Pembayaran Sukses</span>
                    </a>
                    <span class="text-[10px] text-gray-400 text-center block mt-1">Hanya terlihat oleh Admin/Penjaga di lingkungan lokal untuk pengujian.</span>
                </div>
                @endif
            </div>

            <!-- Right: Upload Bukti / Rincian Paket -->
            <div class="md:col-span-6 space-y-6">
                <!-- Rincian Pesanan -->
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 text-xs space-y-3">
                    <h4 class="font-bold text-gray-800 text-sm border-b border-gray-200 pb-2">Rincian Reservasi</h4>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tamu Pemesan</span>
                        <span class="font-bold text-primary">{{ $booking->nama_tamu }} ({{ $booking->no_wa }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paket Villa</span>
                        <span class="font-bold text-primary">{{ $booking->bookingRooms->first()?->roomType?->nama ?? 'Paket Villa' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Menginap</span>
                        <span class="font-bold text-primary">{{ $booking->check_in->format('d/m/Y') }} – {{ $booking->check_out->format('d/m/Y') }} ({{ $booking->duration_nights }} Malam)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kapasitas</span>
                        <span class="font-bold text-primary">{{ $booking->jumlah_tamu }} Orang</span>
                    </div>

                    @if($booking->bookingAddOns->count() > 0)
                    <div class="pt-2 border-t border-gray-200">
                        <span class="font-bold text-gray-700 block mb-1">Layanan Add-On:</span>
                        @foreach($booking->bookingAddOns as $ba)
                        <div class="flex justify-between text-gray-600">
                            <span>• {{ $ba->addOn->nama }} (x{{ $ba->jumlah }})</span>
                            <span>Rp {{ number_format($ba->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="pt-2 border-t border-gray-200 flex justify-between font-bold text-sm text-primary">
                        <span>Total Keseluruhan</span>
                        <span>{{ $booking->formatted_total }}</span>
                    </div>
                </div>

                <!-- Form Upload Bukti Transfer (Bila metode manual transfer dan belum diverifikasi) -->
                @if($booking->status === 'menunggu_pembayaran' && $latestPayment && $latestPayment->metode_pembayaran === 'manual_transfer')
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="font-bold text-sm text-gray-800 mb-2">Upload Bukti Transfer</h4>
                    <p class="text-xs text-gray-500 mb-4">Sudah mentransfer lewat ATM / m-Banking? Unggah foto struk/screenshot di sini:</p>

                    <form action="{{ route('booking.upload_proof', $booking->kode_booking) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <input type="date" name="tanggal_transfer" value="{{ now()->format('Y-m-d') }}" required class="w-full text-xs p-2.5 rounded-xl border border-gray-200">
                        </div>
                        <div>
                            <input type="file" name="file_bukti" required accept="image/*" class="w-full text-xs p-2 rounded-xl border border-gray-200 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-primary file:text-white">
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-primary hover:bg-primary-light text-white font-bold text-xs transition-colors">
                            Kirim Bukti Transfer
                        </button>
                    </form>
                </div>
                @endif

                <!-- WhatsApp Confirmation Action -->
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
                    <span class="text-xs text-emerald-900 font-medium block mb-2">Perlu bantuan atau ingin konfirmasi manual via WA?</span>
                    <a href="https://wa.me/62{{ ltrim($villaWa, '0') }}?text=Halo%20Villa%20Bukit%20Bunga%2C%20saya%20sudah%20membuat%20pesanan%20dengan%20Kode%20Booking%3A%20{{ $booking->kode_booking }}%20atas%20nama%20{{ urlencode($booking->nama_tamu) }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-colors shadow-sm">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Konfirmasi ke WhatsApp Pengelola</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($booking->status === 'menunggu_pembayaran' && $booking->hold_expires_at)
@push('scripts')
<script>
    const expiresAt = new Date("{{ $booking->hold_expires_at->toIso8601String() }}").getTime();

    const timerInterval = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiresAt - now;

        if (distance < 0) {
            clearInterval(timerInterval);
            document.getElementById('countdown-timer').innerText = "WAKTU HABIS";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('countdown-timer').innerText = 
            String(hours).padStart(2, '0') + ":" + 
            String(minutes).padStart(2, '0') + ":" + 
            String(seconds).padStart(2, '0');
    }, 1000);
</script>
@endpush
@endif
@endsection
