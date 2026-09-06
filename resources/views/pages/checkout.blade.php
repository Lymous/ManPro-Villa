@extends('layouts.app')

@section('title', 'Checkout Reservasi - ' . $package->nama)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-3xl mb-8">
        <span class="text-xs uppercase tracking-[0.2em] font-semibold text-accent block mb-1">Langkah 2 dari 3</span>
        <h1 class="font-display text-2xl sm:text-4xl font-bold text-primary">
            Konfirmasi & Pembayaran Reservasi
        </h1>
        <p class="text-gray-500 text-sm mt-1">Lengkapi data pemesan dan pilih metode pembayaran yang Anda inginkan.</p>
    </div>

    <form action="{{ route('booking.store') }}" method="POST" id="checkout-form">
        @csrf
        <input type="hidden" name="room_type_id" value="{{ $package->id }}">
        <input type="hidden" name="check_in" value="{{ $checkIn }}">
        <input type="hidden" name="check_out" value="{{ $checkOut }}">
        <input type="hidden" name="jumlah_tamu" value="{{ $guests }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left: Formulir Data Tamu & Add-ons -->
            <div class="lg:col-span-7 space-y-8">
                <!-- Section 1: Data Tamu -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-display text-xl font-bold text-primary flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-accent/20 text-accent font-sans text-xs font-bold flex items-center justify-center">1</span>
                            Data Tamu Pemesan
                        </h2>
                        <span class="text-xs text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full font-bold border border-emerald-200 inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Akun: {{ auth()->user()->name }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap Pemesan *</label>
                            <input type="text" name="nama_tamu" value="{{ old('nama_tamu', auth()->check() ? auth()->user()->name : '') }}" required placeholder="Contoh: Budi Santoso" 
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                            @error('nama_tamu') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif *</label>
                                <input type="tel" name="no_wa" value="{{ old('no_wa', auth()->check() ? auth()->user()->phone : '') }}" required placeholder="Contoh: 081234567890" 
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                                <span class="text-[10px] text-gray-400 mt-1 block">Kode booking & invoice akan dikirim ke nomor ini.</span>
                                @error('no_wa') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email (Opsional)</label>
                                <input type="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" placeholder="budi@example.com" 
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Khusus / Acara (Opsional)</label>
                            <textarea name="catatan" rows="2" placeholder="Contoh: Acara reuni keluarga besar, request tambahan arang bakar..." 
                                      class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Layanan Tambahan (Add-Ons) -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="font-display text-xl font-bold text-primary mb-2 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-accent/20 text-accent font-sans text-xs font-bold flex items-center justify-center">2</span>
                        Pilihan Fasilitas Tambahan (Add-On)
                    </h2>
                    <p class="text-gray-500 text-xs mb-6">Pilih layanan ekstra untuk menambah kenyamanan acara Anda.</p>

                    <div class="space-y-4">
                        @foreach($addOns as $ao)
                        <div class="p-4 rounded-2xl border border-gray-100 hover:border-accent/40 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-accent text-lg shrink-0">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-primary">{{ $ao->nama }}</h4>
                                    <p class="text-xs text-gray-500 leading-snug">{{ $ao->deskripsi }}</p>
                                    <span class="text-xs font-bold text-accent block mt-1">Rp {{ number_format($ao->harga, 0, ',', '.') }} <span class="font-normal text-gray-400">/ {{ str_replace('_', ' ', $ao->satuan) }}</span></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <span class="text-xs text-gray-400">Qty:</span>
                                <input type="number" 
                                       name="addons[{{ $ao->id }}]" 
                                       value="0" 
                                       min="0" 
                                       max="30" 
                                       data-price="{{ $ao->harga }}" 
                                       class="addon-qty w-16 p-2 text-center rounded-xl border border-gray-200 text-sm font-bold focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section 3: Pilihan Pembayaran -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="font-display text-xl font-bold text-primary mb-2 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-accent/20 text-accent font-sans text-xs font-bold flex items-center justify-center">3</span>
                        Pilih Saluran Pembayaran
                    </h2>
                    <p class="text-gray-500 text-xs mb-6">Pilih salah satu metode pembayaran di bawah ini:</p>

                    <div class="space-y-3">
                        <!-- QRIS -->
                        <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="metode_pembayaran" value="QRIS2" checked class="accent-accent w-4 h-4">
                                <div>
                                    <span class="font-bold text-sm text-primary block">QRIS (Gopay, ShopeePay, OVO, DANA, Livin, BCA Mobile)</span>
                                    <span class="text-xs text-gray-500">Scan instan via seluruh aplikasi perbankan & dompet digital</span>
                                </div>
                            </div>
                            <span class="text-[11px] bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full shrink-0">Instan</span>
                        </label>

                        <!-- Virtual Accounts -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="BCAVA" class="accent-accent w-4 h-4">
                                    <span class="font-bold text-sm text-primary">BCA Virtual Account</span>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="MANDIRIVA" class="accent-accent w-4 h-4">
                                    <span class="font-bold text-sm text-primary">Mandiri Virtual Account</span>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="BRIVA" class="accent-accent w-4 h-4">
                                    <span class="font-bold text-sm text-primary">BRI Virtual Account</span>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="BNIVA" class="accent-accent w-4 h-4">
                                    <span class="font-bold text-sm text-primary">BNI Virtual Account</span>
                                </div>
                            </label>
                        </div>

                        <!-- Convenience Store -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="ALFAMART" class="accent-accent w-4 h-4">
                                    <span class="font-bold text-sm text-primary">Alfamart / Alfamidi</span>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="INDOMARET" class="accent-accent w-4 h-4">
                                    <span class="font-bold text-sm text-primary">Indomaret</span>
                                </div>
                            </label>
                        </div>

                        <!-- Transfer Manual Backup -->
                        <div class="pt-2">
                            <label class="p-4 rounded-2xl border-2 border-gray-200 hover:border-accent cursor-pointer flex items-center justify-between transition-all has-[:checked]:border-accent has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="manual_transfer" class="accent-accent w-4 h-4">
                                    <div>
                                        <span class="font-bold text-sm text-primary block">Transfer Bank Manual (Rekening BCA Villa Bukit Bunga)</span>
                                        <span class="text-xs text-gray-500">Gunakan kode unik transfer, konfirmasi verifikasi oleh admin</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Ringkasan Biaya & Sticky Tombol -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-xl space-y-6">
                    <h3 class="font-display text-xl font-bold text-primary border-b border-gray-100 pb-4">
                        Rincian Pesanan
                    </h3>

                    <!-- Villa Overview -->
                    <div class="flex gap-4 items-center">
                        <img src="{{ $package->thumbnail }}" alt="{{ $package->nama }}" class="w-16 h-16 rounded-2xl object-cover">
                        <div>
                            <h4 class="font-bold text-sm text-primary">{{ $package->nama }}</h4>
                            <p class="text-xs text-gray-500">{{ $package->kapasitas_min }} - {{ $package->kapasitas_max }} Tamu</p>
                            <p class="text-xs text-accent font-semibold">Pacet, Mojokerto</p>
                        </div>
                    </div>

                    <!-- Stay Details -->
                    <div class="bg-gray-50 p-4 rounded-2xl text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Check-In</span>
                            <span class="font-bold text-primary">{{ \Carbon\Carbon::parse($checkIn)->format('d M Y') }} (14:00)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Check-Out</span>
                            <span class="font-bold text-primary">{{ \Carbon\Carbon::parse($checkOut)->format('d M Y') }} (12:00)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Durasi</span>
                            <span class="font-bold text-primary">{{ $nights }} Malam</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Estimasi Tamu</span>
                            <span class="font-bold text-primary">{{ $guests }} Orang</span>
                        </div>
                    </div>

                    <!-- Skema Bayar: DP 50% vs Lunas -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Skema Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="p-3 rounded-2xl border-2 border-gray-200 has-[:checked]:border-accent has-[:checked]:bg-amber-50/30 cursor-pointer text-center">
                                <input type="radio" name="skema_bayar" value="dp" checked class="sr-only">
                                <span class="block text-xs font-bold text-primary">Bayar DP ({{ $dpPercentage }}%)</span>
                                <span class="text-[10px] text-gray-500">Pelunasan saat tiba</span>
                            </label>

                            <label class="p-3 rounded-2xl border-2 border-gray-200 has-[:checked]:border-accent has-[:checked]:bg-amber-50/30 cursor-pointer text-center">
                                <input type="radio" name="skema_bayar" value="lunas" class="sr-only">
                                <span class="block text-xs font-bold text-primary">Bayar Lunas (100%)</span>
                                <span class="text-[10px] text-gray-500">Bebas repot di lokasi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Calculations -->
                    <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Sewa Villa ({{ $nights }} Malam)</span>
                            <span class="font-medium" id="calc-room-price">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Total Add-On</span>
                            <span class="font-medium" id="calc-addon-price">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-900 font-bold pt-2 border-t border-gray-100 text-sm">
                            <span>Total Tagihan Reservasi</span>
                            <span class="font-display text-base text-primary" id="calc-grand-total">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-accent-dark font-bold pt-2 text-sm bg-accent/10 p-3 rounded-xl">
                            <span>Tagihan yang Harus Dibayar Sekarang:</span>
                            <span class="font-display text-lg text-accent-dark" id="calc-payable-now">Rp {{ number_format($dpAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-xl hover:shadow-2xl flex items-center justify-center gap-2 transition-all duration-300">
                        <span>Konfirmasi & Buat Tagihan</span>
                        <i class="fa-solid fa-shield-check text-accent"></i>
                    </button>

                    <div class="text-center">
                        <p class="text-[11px] text-gray-400">
                            Dengan mengklik tombol di atas, Anda menyetujui ketentuan masa hold pembayaran 24 jam dan kebijakan menginap Villa Bukit Bunga Pacet.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const baseRoomPrice = {{ $basePrice }};
    const dpPercent = {{ $dpPercentage }};

    function recalculate() {
        let addonTotal = 0;
        document.querySelectorAll('.addon-qty').forEach(input => {
            const qty = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            addonTotal += (qty * price);
        });

        const grandTotal = baseRoomPrice + addonTotal;
        const skema = document.querySelector('input[name="skema_bayar"]:checked').value;

        let payableNow = grandTotal;
        if (skema === 'dp') {
            payableNow = Math.round((grandTotal * dpPercent) / 100);
        }

        document.getElementById('calc-addon-price').innerText = 'Rp ' + addonTotal.toLocaleString('id-ID');
        document.getElementById('calc-grand-total').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        document.getElementById('calc-payable-now').innerText = 'Rp ' + payableNow.toLocaleString('id-ID');
    }

    document.querySelectorAll('.addon-qty').forEach(input => {
        input.addEventListener('input', recalculate);
    });

    document.querySelectorAll('input[name="skema_bayar"]').forEach(radio => {
        radio.addEventListener('change', recalculate);
    });

    recalculate();
</script>
@endpush
@endsection
