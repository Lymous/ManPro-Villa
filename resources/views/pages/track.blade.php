@extends('layouts.app')

@section('title', 'Cek Status Reservasi - Villa Bukit Bunga Pacet')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <span class="text-xs uppercase tracking-[0.2em] font-semibold text-accent block mb-2">Pelacak Pesanan</span>
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-primary mb-3">
            Cek Status Reservasi Villa
        </h1>
        <p class="text-gray-500 text-sm max-w-lg mx-auto">
            Masukkan kode booking unik Anda (contoh: <strong class="text-primary font-mono">VBB-20260906-XXXX</strong>) untuk memeriksa status pembayaran dan jadwal menginap.
        </p>
    </div>

    <!-- Search Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-lg mb-8">
        <form action="{{ route('booking.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" 
                   name="kode_booking" 
                   value="{{ request('kode_booking') }}" 
                   required 
                   placeholder="Masukkan Kode Booking..." 
                   class="flex-1 px-4 py-3.5 rounded-2xl border border-gray-200 font-mono text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none uppercase">
            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-xs text-accent"></i>
                <span>Cari Pesanan</span>
            </button>
        </form>
    </div>

    <!-- Search Result -->
    @if(request()->filled('kode_booking'))
        @if($booking)
        <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xl p-6 sm:p-8 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-5 mb-5">
                <div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Hasil Pencarian</span>
                    <h3 class="font-display text-2xl font-bold text-primary mt-0.5">{{ $booking->kode_booking }}</h3>
                    <p class="text-xs text-gray-500">Atas Nama: <strong class="text-primary">{{ $booking->nama_tamu }}</strong></p>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold border {{ $booking->status_badge_class }}">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        {{ $booking->status_label }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-6">
                <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="text-gray-400 block mb-1">Paket yang Dipesan</span>
                    <span class="font-bold text-sm text-primary">{{ $booking->bookingRooms->first()?->roomType?->nama ?? 'Paket Villa' }}</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="text-gray-400 block mb-1">Jadwal Menginap</span>
                    <span class="font-bold text-sm text-primary">{{ $booking->check_in->format('d M Y') }} s/d {{ $booking->check_out->format('d M Y') }} ({{ $booking->duration_nights }} Malam)</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="text-gray-400 block mb-1">Total Biaya</span>
                    <span class="font-bold text-sm text-primary">{{ $booking->formatted_total }}</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="text-gray-400 block mb-1">Sisa Kekurangan</span>
                    <span class="font-bold text-sm text-rose-600">Rp {{ number_format($booking->remaining_balance, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="text-right">
                <a href="{{ route('booking.invoice', $booking->kode_booking) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary hover:bg-accent text-white hover:text-primary text-xs font-bold transition-all shadow-sm">
                    <span>Buka Halaman Tagihan & Tiket Lengkap</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
        @else
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-12 text-center text-gray-500">
            <i class="fa-solid fa-face-frown text-4xl text-gray-300 mb-3 block"></i>
            <h4 class="font-bold text-gray-700 text-base mb-1">Pesanan Tidak Ditemukan</h4>
            <p class="text-xs text-gray-400">Pastikan kode booking yang Anda masukkan sudah benar.</p>
        </div>
        @endif
    @endif
</div>
@endsection
