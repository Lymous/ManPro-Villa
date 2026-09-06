@extends('layouts.admin')

@section('title', 'Dasbor & Kalender - Villa Bukit Bunga')
@section('page_title', 'Dasbor Operasional Villa')

@push('styles')
<!-- FullCalendar CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Check-in Hari Ini -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Check-In Hari Ini</span>
                <span class="font-display text-3xl font-bold text-primary">{{ $checkInsToday->count() }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">Rombongan tiba hari ini</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary/5 text-primary border border-primary/10 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-door-open"></i>
            </div>
        </div>

        <!-- Check-out Hari Ini -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Check-Out Hari Ini</span>
                <span class="font-display text-3xl font-bold text-primary">{{ $checkOutsToday->count() }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">Jadwal kepulangan tamu</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-800 border border-amber-200/60 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <!-- Sedang Menginap -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Sedang Menginap</span>
                <span class="font-display text-3xl font-bold text-primary">{{ $activeGuestsCount }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">Rombongan di villa saat ini</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200/60 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-house-user"></i>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <!-- Pendapatan Bulan Ini (Khusus Admin) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Omzet Bulan Ini</span>
                <span class="font-display text-2xl font-bold text-primary">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">Total: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-accent/15 text-primary border border-accent/30 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>
        @else
        <!-- Kamar Tersedia (Untuk Penjaga) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Kamar Fisik Siap</span>
                <span class="font-display text-3xl font-bold text-primary">{{ $availableRooms }} / {{ $totalRooms }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">{{ $occupiedRooms }} Terisi, {{ $maintenanceRooms }} Maintenance</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary/5 text-primary border border-primary/10 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-bed"></i>
            </div>
        </div>
        @endif
    </div>

    <!-- Check-In / Check-Out Hari Ini Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- List Check-in Hari Ini -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-display text-lg font-bold text-primary mb-4 flex items-center gap-2">
                <i class="fa-solid fa-calendar-day text-indigo-500 text-sm"></i>
                Jadwal Kedatangan Tamu Hari Ini
            </h3>
            
            @if($checkInsToday->count() > 0)
            <div class="space-y-3">
                @foreach($checkInsToday as $b)
                <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-primary">{{ $b->nama_tamu }}</span>
                            <span class="text-xs text-gray-500">({{ $b->no_wa }})</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $b->bookingRooms->first()?->roomType?->nama ?? 'Paket' }} • {{ $b->jumlah_tamu }} Orang
                        </p>
                        <p class="text-[11px] text-accent font-semibold mt-1">
                            Unit Kamar: {{ $b->bookingRooms->first()?->room?->nomor_kamar ?? 'Belum Ditugaskan' }}
                        </p>
                    </div>

                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="px-3.5 py-1.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-light transition-colors">
                        Kelola
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 italic py-6 text-center">Tidak ada jadwal kedatangan check-in baru hari ini.</p>
            @endif
        </div>

        <!-- List Check-Out Hari Ini -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-display text-lg font-bold text-primary mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-amber-500 text-sm"></i>
                Jadwal Kepulangan Tamu Hari Ini
            </h3>

            @if($checkOutsToday->count() > 0)
            <div class="space-y-3">
                @foreach($checkOutsToday as $b)
                <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-primary">{{ $b->nama_tamu }}</span>
                            <span class="text-xs text-gray-500">({{ $b->no_wa }})</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Kamar: {{ $b->bookingRooms->pluck('room.nomor_kamar')->filter()->implode(', ') ?: 'Standar' }}
                        </p>
                    </div>

                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="px-3.5 py-1.5 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 transition-colors">
                        Check-Out
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 italic py-6 text-center">Tidak ada jadwal kepulangan check-out hari ini.</p>
            @endif
        </div>
    </div>

    <!-- Kalender Okupansi Visual -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-display text-xl font-bold text-primary">Kalender Okupansi Villa</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pantau jadwal menginap seluruh paket dan tanggal blokir pemeliharaan</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Lunas</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span> DP Terbayar</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-500"></span> Check-In</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span> Blokir Perawatan</span>
            </div>
        </div>

        <div id="calendar" class="p-2"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            locale: 'id',
            events: @json($calendarEvents),
            eventClick: function(info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            }
        });
        calendar.render();
    });
</script>
@endpush
@endsection
