@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Villa Bukit Bunga')
@section('page_title', 'Audit & Verifikasi Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-display text-xl font-bold text-primary">Daftar Transaksi Pembayaran</h3>
            <p class="text-xs text-gray-500">Audit pembayaran online (QRIS / Virtual Account) dan verifikasi bukti transfer manual</p>
        </div>
    </div>

    <!-- Payment List Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-gray-400 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-4 px-6">ID / Ref</th>
                        <th class="py-4 px-4">Kode Booking</th>
                        <th class="py-4 px-4">Tamu</th>
                        <th class="py-4 px-4">Jenis</th>
                        <th class="py-4 px-4">Saluran / Metode</th>
                        <th class="py-4 px-4">Nominal</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4">Bukti Transfer</th>
                        <th class="py-4 px-6 text-right">Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($payments as $p)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="py-4 px-6 font-mono text-[11px] text-gray-500">
                            {{ $p->tripay_reference ?? ('PAY-' . $p->id) }}
                            <span class="block text-[10px] text-gray-400">{{ $p->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="py-4 px-4 font-mono font-bold text-primary">
                            <a href="{{ route('admin.bookings.show', $p->booking_id) }}" class="hover:text-accent">
                                {{ $p->booking->kode_booking }}
                            </a>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-gray-900 block">{{ $p->booking->nama_tamu }}</span>
                            <span class="text-[10px] text-gray-500">{{ $p->booking->no_wa }}</span>
                        </td>
                        <td class="py-4 px-4 uppercase text-[10px] font-bold text-gray-500">
                            {{ $p->jenis }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-semibold text-primary block">{{ $p->tripay_channel ?? $p->metode_pembayaran }}</span>
                        </td>
                        <td class="py-4 px-4 font-bold text-primary whitespace-nowrap">
                            {{ $p->formatted_nominal }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $p->status_badge_class }}">
                                {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            @if($p->file_bukti)
                            <a href="{{ $p->file_bukti }}" target="_blank" class="inline-flex items-center gap-1 text-accent hover:underline font-bold text-[11px]">
                                <i class="fa-solid fa-image"></i> Lihat Bukti
                            </a>
                            @else
                            <span class="text-gray-400 text-[11px] italic">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            @if($p->status === 'menunggu_konfirmasi')
                            <div class="flex items-center justify-end gap-1.5">
                                <form action="{{ route('admin.payments.verify', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-colors">
                                        Verifikasi
                                    </button>
                                </form>
                                <form action="{{ route('admin.payments.reject', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-[11px] transition-colors">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-[10px] text-gray-400">
                                {{ $p->verifier ? 'Diverifikasi: ' . $p->verifier->name : 'Otomatis' }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-12 text-gray-400">
                            Belum ada riwayat transaksi pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
