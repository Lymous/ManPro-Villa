@extends('layouts.admin')

@section('title', 'Data Reservasi - Villa Bukit Bunga')
@section('page_title', 'Manajemen Pemesanan Villa')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-display text-xl font-bold text-primary">Daftar Seluruh Reservasi</h3>
            <p class="text-xs text-gray-500">Kelola jadwal tamu, penetapan kamar, dan status transaksi</p>
        </div>

        <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-primary hover:bg-primary-light text-white text-xs font-bold transition-all shadow-sm">
            <i class="fa-solid fa-plus text-accent"></i>
            <span>Input Booking Manual (WA/Offline)</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Booking / Nama Tamu / No WA..." 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-accent outline-none">
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-accent outline-none">
                    <option value="">Semua Status</option>
                    <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="dp_dibayar" {{ request('status') === 'dp_dibayar' ? 'selected' : '' }}>DP Terbayar</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="check_in" {{ request('status') === 'check_in' ? 'selected' : '' }}>Check-In (Menginap)</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <input type="date" name="check_in" value="{{ request('check_in') }}" 
                       class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-accent outline-none">
            </div>

            <div class="sm:col-span-1">
                <button type="submit" class="w-full h-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-colors">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-gray-400 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-4 px-6">Kode Booking</th>
                        <th class="py-4 px-4">Tamu & Kontak</th>
                        <th class="py-4 px-4">Paket Dipesan</th>
                        <th class="py-4 px-4">Jadwal Menginap</th>
                        <th class="py-4 px-4">Unit Kamar Fisik</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4">Total Biaya</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-primary">
                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="hover:text-accent">
                                {{ $b->kode_booking }}
                            </a>
                            <span class="block text-[10px] text-gray-400 font-normal">
                                Sumber: {{ ucfirst($b->sumber) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-gray-900 block">{{ $b->nama_tamu }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $b->no_wa) }}" target="_blank" class="text-[11px] text-emerald-600 hover:underline">
                                <i class="fa-brands fa-whatsapp mr-1"></i>{{ $b->no_wa }}
                            </a>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-semibold block line-clamp-1 max-w-[160px]">{{ $b->bookingRooms->first()?->roomType?->nama ?? '-' }}</span>
                            <span class="text-[10px] text-gray-400">{{ $b->jumlah_tamu }} Orang</span>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="block font-bold">{{ $b->check_in->format('d/m/Y') }}</span>
                            <span class="text-[10px] text-gray-400">s/d {{ $b->check_out->format('d/m/Y') }} ({{ $b->duration_nights }} mlm)</span>
                        </td>
                        <td class="py-4 px-4">
                            @php
                                $assignedRooms = $b->bookingRooms->pluck('room.nomor_kamar')->filter();
                            @endphp
                            @if($assignedRooms->count() > 0)
                                <span class="bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded text-[11px]">
                                    {{ $assignedRooms->implode(', ') }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-[11px]">Belum Ditugaskan</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $b->status_badge_class }}">
                                {{ $b->status_label }}
                            </span>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="font-bold text-primary block">{{ $b->formatted_total }}</span>
                            @if($b->remaining_balance > 0)
                            <span class="text-[10px] text-rose-500 font-semibold">Sisa: Rp {{ number_format($b->remaining_balance, 0, ',', '.') }}</span>
                            @else
                            <span class="text-[10px] text-emerald-600 font-semibold">Lunas</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-primary hover:text-white text-gray-700 font-bold transition-colors">
                                Rincian
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-400">
                            Belum ada data reservasi yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
