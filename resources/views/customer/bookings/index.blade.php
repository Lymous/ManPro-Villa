@extends('layouts.app')

@section('title', 'Pesanan Saya - Villa Bukit Bunga Pacet')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Profile & Welcome Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-primary text-accent font-display font-bold text-2xl flex items-center justify-center shrink-0 shadow-md">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-display text-2xl font-bold text-primary">{{ auth()->user()->name }}</h1>
                    <span class="bg-accent/15 text-accent-dark text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                        Akun Tamu
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">
                    <i class="fa-regular fa-envelope mr-1"></i> {{ auth()->user()->email }} • 
                    <i class="fa-brands fa-whatsapp text-emerald-600 mr-1 ml-1"></i> {{ auth()->user()->phone ?? 'Belum ada No WA' }}
                </p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="flex items-center gap-4">
            <div class="bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100 text-center">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Pesanan Aktif</span>
                <span class="font-display text-2xl font-bold text-primary">{{ $activeBookingsCount }}</span>
            </div>
            <div class="bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100 text-center">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Selesai Menginap</span>
                <span class="font-display text-2xl font-bold text-emerald-600">{{ $completedBookingsCount }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-3 rounded-2xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors text-xs font-bold" title="Keluar Akun">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Booking List Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-xl font-bold text-primary">Riwayat Reservasi Villa</h2>
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-primary hover:bg-primary-light text-white text-xs font-bold transition-all shadow-xs">
                <i class="fa-solid fa-plus text-accent text-[10px]"></i>
                <span>Pesan Villa Baru</span>
            </a>
        </div>

        @forelse($bookings as $b)
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-6">
            <!-- Left Info -->
            <div class="space-y-3 flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="font-mono text-base font-bold text-primary">{{ $b->kode_booking }}</span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border {{ $b->status_badge_class }}">
                        {{ $b->status_label }}
                    </span>
                    <span class="text-xs text-gray-400">Dipesan pada {{ $b->created_at->format('d M Y') }}</span>
                </div>

                <div>
                    <h3 class="font-display text-lg font-bold text-primary">
                        {{ $b->bookingRooms->first()?->roomType?->nama ?? 'Paket Villa' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <i class="fa-regular fa-calendar-days text-accent mr-1"></i>
                        {{ $b->check_in->format('d M Y') }} s/d {{ $b->check_out->format('d M Y') }} ({{ $b->duration_nights }} Malam) • 
                        <i class="fa-solid fa-users text-accent mr-1 ml-2"></i> {{ $b->jumlah_tamu }} Orang
                    </p>
                </div>

                @if($b->bookingRooms->first()?->room)
                <p class="text-xs text-indigo-700 font-bold bg-indigo-50/70 inline-block px-3 py-1 rounded-lg">
                    <i class="fa-solid fa-key mr-1"></i> Unit Kamar Ditetapkan: {{ $b->bookingRooms->first()->room->nomor_kamar }}
                </p>
                @endif
            </div>

            <!-- Right Price & CTA -->
            <div class="text-left md:text-right shrink-0 border-t md:border-t-0 pt-4 md:pt-0 border-gray-100 flex flex-row md:flex-col justify-between items-center md:items-end gap-3">
                <div>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Total Pembayaran</span>
                    <span class="font-display text-xl font-bold text-primary">{{ $b->formatted_total }}</span>
                    @if($b->remaining_balance > 0)
                    <span class="text-[11px] text-rose-500 font-bold block">Sisa: Rp {{ number_format($b->remaining_balance, 0, ',', '.') }}</span>
                    @else
                    <span class="text-[11px] text-emerald-600 font-bold block">Lunas ✓</span>
                    @endif
                </div>

                <a href="{{ route('booking.invoice', $b->kode_booking) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary hover:bg-accent text-white hover:text-primary text-xs font-bold transition-all shadow-sm">
                    <span>Buka Tagihan & Tiket</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center text-3xl mx-auto">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <div>
                <h3 class="font-bold text-primary text-base">Belum Ada Riwayat Reservasi</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">Anda belum melakukan pemesanan paket villa. Pilih paket favorit Anda sekarang dan nikmati liburan di Pacet!</p>
            </div>
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-white text-xs font-bold shadow-md hover:bg-primary-light transition-all">
                <span>Lihat Pilihan Paket Villa</span>
                <i class="fa-solid fa-arrow-right text-[10px] text-accent"></i>
            </a>
        </div>
        @endforelse

        <div class="pt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
