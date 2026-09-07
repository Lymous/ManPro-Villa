@extends('layouts.admin')

@section('title', 'Detail Reservasi ' . $booking->kode_booking)
@section('page_title', 'Rincian Reservasi ' . $booking->kode_booking)

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="font-mono text-2xl font-bold text-primary">{{ $booking->kode_booking }}</span>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border {{ $booking->status_badge_class }}">
                    {{ $booking->status_label }}
                </span>
            </div>
            <p class="text-xs text-gray-500">
                Dibuat: {{ $booking->created_at->format('d M Y H:i') }} WIB • 
                Sumber: <strong class="text-primary">{{ ucfirst($booking->sumber) }}</strong> 
                @if($booking->creator)
                (Diinput oleh: {{ $booking->creator->name }})
                @endif
            </p>
        </div>

        <!-- Quick Status Update Form -->
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <form action="{{ route('admin.bookings.update_status', $booking->id) }}" method="POST" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                @csrf
                <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 text-xs font-bold outline-none flex-1 sm:flex-initial">
                    <option value="menunggu_pembayaran" {{ $booking->status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="dp_dibayar" {{ $booking->status === 'dp_dibayar' ? 'selected' : '' }}>DP Dibayar</option>
                    <option value="lunas" {{ $booking->status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="check_in" {{ $booking->status === 'check_in' ? 'selected' : '' }}>Sedang Menginap (Check-In)</option>
                    <option value="selesai" {{ $booking->status === 'selesai' ? 'selected' : '' }}>Selesai (Check-Out)</option>
                    <option value="batal" {{ $booking->status === 'batal' ? 'selected' : '' }}>Batalkan Pesanan</option>
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-light transition-colors">
                    Perbarui
                </button>
            </form>

            <a href="{{ route('booking.invoice', $booking->kode_booking) }}" target="_blank" class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50 flex items-center justify-center">
                <i class="fa-solid fa-receipt mr-1"></i> Buka Invoice
            </a>
        </div>
    </div>

    <!-- Main Grid Details -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Kamar, Add-on, Penugasan Kamar Fisik -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Rincian Kamar & Penetapan Unit Fisik -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-sm space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 border-b border-gray-100 pb-3">
                    <h3 class="font-display text-lg font-bold text-primary">Kamar Dipesan & Penetapan Kamar Fisik</h3>
                    <span class="text-xs font-sans text-gray-400 font-normal">Wajib penetapan fisik sebelum/saat check-in</span>
                </div>

                @foreach($booking->bookingRooms as $br)
                <div class="p-4 sm:p-5 rounded-2xl border border-gray-200 bg-gray-50/70 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 pb-3">
                        <div>
                            <h4 class="font-bold text-sm text-primary">{{ $br->roomType->nama }}</h4>
                            <span class="text-xs text-gray-500">{{ $br->jumlah_kamar }} unit x {{ $br->jumlah_malam }} malam</span>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="font-bold text-sm text-primary">Rp {{ number_format($br->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Room Assignment Form -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Unit Kamar Fisik yang Ditempati:</span>
                            @if($br->room)
                                <span class="font-bold text-sm text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg inline-block mt-1">
                                    <i class="fa-solid fa-key mr-1.5"></i> Kamar {{ $br->room->nomor_kamar }} ({{ $br->room->roomType->nama }})
                                </span>
                            @else
                                <span class="text-xs text-rose-600 font-bold italic block mt-1">
                                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Belum Ditetapkan
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('admin.bookings.assign_room', $booking->id) }}" method="POST" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="booking_room_id" value="{{ $br->id }}">
                            <select name="room_id" required class="px-3 py-2 rounded-xl border border-gray-200 text-xs font-bold outline-none flex-1 sm:flex-initial">
                                <option value="">-- Pilih Unit Kamar --</option>
                                @foreach($availableRooms as $r)
                                <option value="{{ $r->id }}" {{ $br->room_id == $r->id ? 'selected' : '' }}>
                                    {{ $r->nomor_kamar }} - {{ $r->roomType->nama }} ({{ ucfirst($r->status) }})
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-accent text-primary text-xs font-bold hover:bg-accent-dark hover:text-white transition-colors whitespace-nowrap">
                                Simpan Unit
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Reschedule Form (Bisa diakses Admin & Penjaga) -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-display text-lg font-bold text-primary">Penjadwalan Ulang (Reschedule)</h3>
                <p class="text-xs text-gray-500">
                    Jadwal Saat Ini: <strong class="text-primary">{{ $booking->check_in->format('d M Y') }} s/d {{ $booking->check_out->format('d M Y') }}</strong> 
                    (Telah di-reschedule: {{ $booking->jumlah_reschedule }}x)
                </p>

                <form action="{{ route('admin.bookings.reschedule', $booking->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">Check-In Baru</label>
                        <input type="date" name="new_check_in" required min="{{ now()->format('Y-m-d') }}" class="w-full text-xs p-2.5 rounded-xl border border-gray-200">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">Check-Out Baru</label>
                        <input type="date" name="new_check_out" required min="{{ now()->addDay()->format('Y-m-d') }}" class="w-full text-xs p-2.5 rounded-xl border border-gray-200">
                    </div>
                    <div>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-colors">
                            Ajukan Reschedule
                        </button>
                    </div>
                </form>
            </div>

            <!-- Riwayat Audit Log (booking_logs) -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-display text-lg font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-accent text-sm"></i>
                    Jejak Audit & Riwayat Aktivitas
                </h3>

                <div class="space-y-3 max-h-60 overflow-y-auto pr-2 text-xs">
                    @forelse($booking->bookingLogs as $log)
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-start justify-between">
                        <div>
                            <span class="font-bold text-primary block">{{ ucfirst(str_replace('_', ' ', $log->aksi)) }}</span>
                            <span class="text-gray-500 text-[11px]">{{ $log->nilai_baru }}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[10px] text-gray-400 block">{{ $log->created_at->format('d M Y H:i') }}</span>
                            <span class="text-[10px] text-accent font-semibold">{{ $log->user ? $log->user->name : 'Sistem' }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">Belum ada riwayat aktivitas yang tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Tamu, Keuangan, Riwayat Pembayaran, Notifikasi WA -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Tamu Info -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-xs space-y-3">
                <h4 class="font-bold text-sm text-primary border-b border-gray-100 pb-2">Informasi Pemesan</h4>
                <div>
                    <span class="text-gray-400 block">Nama Tamu</span>
                    <span class="font-bold text-primary text-sm">{{ $booking->nama_tamu }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">WhatsApp</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->no_wa) }}" target="_blank" class="font-bold text-emerald-600 hover:underline">
                        <i class="fa-brands fa-whatsapp mr-1"></i>{{ $booking->no_wa }}
                    </a>
                </div>
                <div>
                    <span class="text-gray-400 block">Kapasitas Tamu</span>
                    <span class="font-bold text-primary">{{ $booking->jumlah_tamu }} Orang</span>
                </div>
                @if($booking->catatan)
                <div>
                    <span class="text-gray-400 block">Catatan Khusus</span>
                    <p class="text-gray-600 italic bg-gray-50 p-2.5 rounded-xl border border-gray-100">{{ $booking->catatan }}</p>
                </div>
                @endif
            </div>

            <!-- Ringkasan Finansial -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-xs space-y-3">
                <h4 class="font-bold text-sm text-primary border-b border-gray-100 pb-2">Ringkasan Pembayaran</h4>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Harga Sewa</span>
                    <span class="font-bold text-primary">{{ $booking->formatted_total }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kewajiban Uang Muka (DP)</span>
                    <span class="font-bold text-primary">{{ $booking->formatted_dp }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-100 font-bold text-sm">
                    <span>Sisa Tagihan</span>
                    <span class="{{ $booking->remaining_balance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        Rp {{ number_format($booking->remaining_balance, 0, ',', '.') }}
                    </span>
                </div>

                @if(auth()->user()->isAdmin() && $booking->remaining_balance > 0)
                <!-- Form Input Pelunasan / Denda oleh Admin -->
                <div class="pt-3 border-t border-gray-100">
                    <span class="font-bold text-primary block mb-2">Input Pelunasan Manual:</span>
                    <form action="{{ route('admin.payments.settlement', $booking->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="hidden" name="jenis" value="pelunasan">
                        <input type="hidden" name="metode_pembayaran" value="cash_di_villa">
                        <input type="number" name="nominal" value="{{ $booking->remaining_balance }}" required class="w-full text-xs p-2 rounded-xl border border-gray-200 font-bold">
                        <button type="submit" class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors">
                            Tandai Pelunasan Diterima
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Riwayat WhatsApp Notifications (notifications) -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-3">
                <h4 class="font-bold text-sm text-primary flex items-center gap-2 border-b border-gray-100 pb-2">
                    <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                    Log Notifikasi WhatsApp
                </h4>

                <div class="space-y-2 max-h-56 overflow-y-auto text-xs pr-1">
                    @forelse($booking->notifications as $notif)
                    <div class="p-2.5 rounded-xl bg-emerald-50/50 border border-emerald-100">
                        <div class="flex justify-between font-semibold text-[11px] text-emerald-900 mb-1">
                            <span>{{ strtoupper(str_replace('_', ' ', $notif->jenis)) }}</span>
                            <span class="text-[10px] text-emerald-600">{{ $notif->created_at->format('d/m H:i') }}</span>
                        </div>
                        <p class="text-[11px] text-gray-600 leading-snug line-clamp-3">{{ $notif->pesan }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">Belum ada log notifikasi WhatsApp.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
