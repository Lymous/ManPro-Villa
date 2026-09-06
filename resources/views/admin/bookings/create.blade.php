@extends('layouts.admin')

@section('title', 'Input Reservasi Manual - Villa Bukit Bunga')
@section('page_title', 'Catat Pesanan Manual (WhatsApp / Offline / Instagram)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="font-display text-xl font-bold text-primary">Formulir Pesanan Manual Admin</h3>
            <p class="text-xs text-gray-500">Gunakan form ini jika ada tamu yang memesan langsung via WhatsApp, Instagram, atau datang langsung ke lokasi.</p>
        </div>

        <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Sumber & Status Awal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sumber Pemesanan *</label>
                    <select name="sumber" required class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:border-accent outline-none">
                        <option value="whatsapp" selected>WhatsApp Hotline</option>
                        <option value="instagram">Instagram DM</option>
                        <option value="offline">Offline / Datang Langsung</option>
                        <option value="website">Website (Manual Assist)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status Awal Booking *</label>
                    <select name="status" required class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:border-accent outline-none">
                        <option value="menunggu_pembayaran">Menunggu Pembayaran (Hold 24 Jam)</option>
                        <option value="dp_dibayar" selected>DP 50% Sudah Diterima</option>
                        <option value="lunas">Sudah Lunas Penuh (100%)</option>
                    </select>
                </div>
            </div>

            <!-- Pilih Paket Villa -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Paket Villa *</label>
                <select name="room_type_id" required class="w-full p-3 rounded-2xl border border-gray-200 text-sm font-semibold focus:border-accent outline-none">
                    @foreach($roomTypes as $rt)
                    <option value="{{ $rt->id }}">
                        {{ $rt->nama }} - {{ $rt->formatted_price }}/malam (Kapasitas {{ $rt->kapasitas_min }}–{{ $rt->kapasitas_max }} Org)
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Menginap -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Check-In *</label>
                    <input type="date" name="check_in" value="{{ now()->format('Y-m-d') }}" required 
                           class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Check-Out *</label>
                    <input type="date" name="check_out" value="{{ now()->addDay()->format('Y-m-d') }}" required 
                           class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jumlah Tamu (Orang) *</label>
                    <input type="number" name="jumlah_tamu" value="25" min="1" required 
                           class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                </div>
            </div>

            <!-- Data Tamu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Tamu Pemesan *</label>
                    <input type="text" name="nama_tamu" required placeholder="Nama lengkap..." 
                           class="w-full p-3 rounded-2xl border border-gray-200 text-sm focus:border-accent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">No WhatsApp Tamu *</label>
                    <input type="tel" name="no_wa" required placeholder="08xxxxxxxxxx" 
                           class="w-full p-3 rounded-2xl border border-gray-200 text-sm focus:border-accent outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Khusus (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Catatan internal rombongan, request khusus..." 
                          class="w-full p-3 rounded-2xl border border-gray-200 text-sm focus:border-accent outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-primary hover:bg-primary-light text-white text-xs font-bold shadow-md transition-all">
                    Simpan Reservasi Manual
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
