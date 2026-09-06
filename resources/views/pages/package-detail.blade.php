@extends('layouts.app')

@section('title', $package->nama . ' - Villa Bukit Bunga Pacet')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-6 gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
        <span>/</span>
        <a href="{{ route('packages.index') }}" class="hover:text-primary">Paket Villa</a>
        <span>/</span>
        <span class="text-primary font-semibold truncate">{{ $package->nama }}</span>
    </nav>

    <!-- Header Judul & Kapasitas -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-accent/15 text-accent-dark text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    Pacet, Mojokerto
                </span>
                <span class="bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full">
                    Kapasitas {{ $package->kapasitas_min }} - {{ $package->kapasitas_max }} Orang
                </span>
            </div>
            <h1 class="font-display text-2xl sm:text-4xl font-bold text-primary">
                {{ $package->nama }}
            </h1>
        </div>

        <div class="text-right">
            <span class="text-xs text-gray-500 uppercase tracking-wider block">Harga Sewa Mulai</span>
            <div class="flex items-baseline gap-1.5 md:justify-end">
                <span class="font-display text-3xl sm:text-4xl font-bold text-primary">{{ $package->formatted_price }}</span>
                <span class="text-gray-500 text-sm">/malam</span>
            </div>
        </div>
    </div>

    <!-- Gallery Grid (Nginapsini Style) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12 rounded-3xl overflow-hidden shadow-lg">
        <!-- Main Large Image -->
        <div class="md:col-span-2 md:row-span-2 relative h-[320px] md:h-[480px] bg-gray-100 overflow-hidden group">
            <img src="{{ $package->photos->first()?->file_path ?? $package->thumbnail }}" 
                 alt="{{ $package->nama }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
            <span class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-full font-medium">
                <i class="fa-solid fa-camera mr-1"></i> Area Utama Villa
            </span>
        </div>

        <!-- 4 Secondary Images -->
        @foreach($package->photos->slice(1, 4) as $p)
        <div class="relative h-[150px] md:h-[232px] bg-gray-100 overflow-hidden group hidden sm:block">
            <img src="{{ $p->file_path }}" alt="Galeri {{ $package->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
        </div>
        @endforeach
    </div>

    <!-- Main Content & Sticky Booking Form -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Left Column: Details & Amenities -->
        <div class="lg:col-span-7 space-y-10">
            <!-- Deskripsi -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-display text-2xl font-bold text-primary mb-4">Deskripsi Paket</h2>
                <p class="text-gray-600 leading-relaxed text-sm mb-6 whitespace-pre-line">
                    {{ $package->deskripsi }}
                </p>

                <!-- Highlights Box -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-6 border-t border-gray-100 text-center">
                    <div class="bg-cream p-4 rounded-2xl">
                        <i class="fa-solid fa-users text-accent text-xl mb-1 block"></i>
                        <span class="text-xs text-gray-500 block">Kapasitas</span>
                        <span class="font-bold text-sm text-primary">{{ $package->kapasitas_min }}–{{ $package->kapasitas_max }} Tamu</span>
                    </div>
                    <div class="bg-cream p-4 rounded-2xl">
                        <i class="fa-solid fa-water-ladder text-cyan-600 text-xl mb-1 block"></i>
                        <span class="text-xs text-gray-500 block">Kolam Renang</span>
                        <span class="font-bold text-sm text-primary">Termasuk</span>
                    </div>
                    <div class="bg-cream p-4 rounded-2xl">
                        <i class="fa-solid fa-clock text-amber-600 text-xl mb-1 block"></i>
                        <span class="text-xs text-gray-500 block">Check-In</span>
                        <span class="font-bold text-sm text-primary">14:00 WIB</span>
                    </div>
                </div>
            </div>

            <!-- Fasilitas Lengkap -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-display text-2xl font-bold text-primary mb-6">Fasilitas yang Didapatkan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($package->fasilitas && is_array($package->fasilitas))
                        @foreach($package->fasilitas as $fas)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/70 border border-gray-100">
                            <i class="fa-solid fa-circle-check text-accent text-sm"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $fas }}</span>
                        </div>
                        @endforeach
                    @endif
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/70 border border-gray-100">
                        <i class="fa-solid fa-circle-check text-accent text-sm"></i>
                        <span class="text-sm font-medium text-gray-700">Dapur Lengkap & Alat Masak</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/70 border border-gray-100">
                        <i class="fa-solid fa-circle-check text-accent text-sm"></i>
                        <span class="text-sm font-medium text-gray-700">Area Parkir Muat Bis & Mobil</span>
                    </div>
                </div>
            </div>

            <!-- Aturan Menginap -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-display text-2xl font-bold text-primary mb-4">Ketentuan & Kebijakan Villa</h2>
                <ul class="space-y-3 text-xs sm:text-sm text-gray-600">
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-info-circle text-accent mt-0.5"></i>
                        <span>Uang muka (DP) sebesar <strong>{{ $dpPercentage }}%</strong> wajib diselesaikan dalam 24 jam untuk mengamankan tanggal reservasi.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-info-circle text-accent mt-0.5"></i>
                        <span>Pelunasan sisa tagihan dapat dilakukan H-1 atau saat tiba di lokasi sebelum kunci diserahkan.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-info-circle text-accent mt-0.5"></i>
                        <span>Pengajuan penjadwalan ulang (reschedule) dapat dilakukan maksimal 7 hari sebelum tanggal kedatangan.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Column: Sticky Booking Card -->
        <div class="lg:col-span-5">
            <div class="sticky top-28 bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-xl">
                <div class="border-b border-gray-100 pb-5 mb-6">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Reservasi</span>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="font-display text-3xl font-bold text-primary" id="display-total-price">
                            {{ $package->formatted_price }}
                        </span>
                        <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-3 py-1 rounded-full">
                            DP Cukup {{ $dpPercentage }}%
                        </span>
                    </div>
                </div>

                <!-- Form Pemesanan -->
                <form action="{{ route('booking.checkout', $package->slug) }}" method="GET" id="booking-form" class="space-y-5">
                    @guest
                    <div class="p-3.5 rounded-2xl bg-amber-50/90 border border-amber-200 text-xs text-amber-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                        <div class="leading-relaxed">
                            <span class="font-bold block text-amber-950">Login Diperlukan untuk Pemesanan</span>
                            Untuk memesan paket ini, Anda harus masuk atau mendaftar akun terlebih dahulu.
                        </div>
                    </div>
                    @endguest

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Check-In</label>
                            <input type="date" name="check_in" id="check_in" 
                                   value="{{ now()->addDay()->format('Y-m-d') }}" 
                                   min="{{ now()->format('Y-m-d') }}" 
                                   class="w-full p-3 rounded-2xl border border-gray-200 text-xs font-semibold focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Check-Out</label>
                            <input type="date" name="check_out" id="check_out" 
                                   value="{{ now()->addDays(2)->format('Y-m-d') }}" 
                                   min="{{ now()->addDay()->format('Y-m-d') }}" 
                                   class="w-full p-3 rounded-2xl border border-gray-200 text-xs font-semibold focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                        </div>
                    </div>

                    <!-- Guests -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Jumlah Tamu</label>
                        <input type="number" name="guests" id="guests" 
                               value="{{ $package->kapasitas_min }}" 
                               min="1" max="{{ $package->kapasitas_max + 10 }}" 
                               class="w-full p-3 rounded-2xl border border-gray-200 text-xs font-semibold focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                        <span class="text-[10px] text-gray-400 mt-1 block">Rekomendasi paket ini: {{ $package->kapasitas_min }} - {{ $package->kapasitas_max }} orang.</span>
                    </div>

                    <!-- Add-ons Selector preview -->
                    <div class="pt-4 border-t border-gray-100">
                        <span class="block text-xs font-bold text-primary mb-3">Layanan Tambahan (Opsional)</span>
                        <div class="space-y-2.5 max-h-48 overflow-y-auto pr-1">
                            @foreach($addOns as $ao)
                            <label class="flex items-center justify-between p-2.5 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer text-xs">
                                <span class="font-medium text-gray-700">{{ $ao->nama }}</span>
                                <span class="text-accent font-bold">{{ $ao->formatted_price }}</span>
                            </label>
                            @endforeach
                        </div>
                        <span class="text-[10px] text-gray-400 mt-2 block italic">*Pilihan add-on dapat ditentukan di halaman checkout selanjutnya.</span>
                    </div>

                    <!-- Submit CTA -->
                    <div class="pt-4">
                        @guest
                        <button type="submit" class="w-full py-4 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-xl hover:shadow-2xl flex items-center justify-center gap-2 transition-all duration-300">
                            <i class="fa-solid fa-right-to-bracket text-xs text-accent"></i>
                            <span>Masuk & Lanjut Pemesanan</span>
                            <i class="fa-solid fa-arrow-right text-xs text-accent"></i>
                        </button>
                        @else
                        <button type="submit" class="w-full py-4 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-xl hover:shadow-2xl flex items-center justify-center gap-2 transition-all duration-300">
                            <span>Lanjut ke Formulir Pemesanan</span>
                            <i class="fa-solid fa-arrow-right text-xs text-accent"></i>
                        </button>
                        @endguest
                    </div>

                    <div class="text-center">
                        <span class="text-[11px] text-gray-400 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-lock text-emerald-500"></i> Transaksi & Reservasi Aman Terpercaya
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
