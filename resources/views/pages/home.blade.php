@extends('layouts.app')

@section('title', 'Villa Bukit Bunga Pacet - Sewa Villa Keluarga & Gathering')

@section('content')
<!-- HERO SECTION (Nginapsini Style) -->
<section class="relative min-h-screen flex items-center justify-center luxury-gradient text-white overflow-hidden hero-pattern">
    <!-- Ambient Glow & Overlay Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=2000&q=80" 
             alt="Villa Bukit Bunga Pacet" 
             class="w-full h-full object-cover object-center opacity-35 mix-blend-overlay filter brightness-90">
        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/75 to-transparent"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-accent/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 -left-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <div class="relative z-10 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 text-center pt-16 pb-32">
        <!-- Tagline Lokasi (Tanpa Kotak / Garis) -->
        <div class="inline-flex items-center justify-center gap-2 text-accent font-bold text-xs sm:text-sm uppercase tracking-[0.25em] mb-4 drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">
            <i class="fa-solid fa-location-dot text-accent"></i>
            <span>Pacet, Mojokerto • Jawa Timur</span>
        </div>

        <!-- Headline Besar -->
        <h1 class="font-display text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-bold tracking-tight leading-[1.05] mb-5">
            Complete Your <br>
            <span class="italic text-accent font-normal">Every Moment</span>
        </h1>

        <p class="text-white/85 text-base sm:text-lg max-w-3xl mx-auto leading-relaxed mb-10 font-normal">
            Villa Keluarga & Rombongan Gathering di Pacet. Cocok untuk Reuni, Acara Sekolah, Kampus, LDKS, dan Komunitas dengan Kolam Renang & Aula Sound System.
        </p>

        <!-- Quick Booking Search Bar (Floating & Lebih Luas) -->
        <div class="card-glass p-4 sm:p-6 rounded-3xl shadow-2xl border border-white/40 max-w-5xl mx-auto text-left text-gray-800">
            <form action="{{ route('packages.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Check-in -->
                <div class="sm:col-span-4 bg-gray-50/90 rounded-2xl p-3 border border-gray-200/80 hover:border-accent transition-colors">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                        <i class="fa-regular fa-calendar-check text-accent mr-1"></i> Tanggal Check-In
                    </label>
                    <input type="date" name="check_in" value="{{ now()->addDay()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" class="w-full bg-transparent text-sm font-semibold text-gray-800 focus:outline-none cursor-pointer">
                </div>

                <!-- Check-out -->
                <div class="sm:col-span-4 bg-gray-50/90 rounded-2xl p-3 border border-gray-200/80 hover:border-accent transition-colors">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                        <i class="fa-regular fa-calendar-xmark text-accent mr-1"></i> Tanggal Check-Out
                    </label>
                    <input type="date" name="check_out" value="{{ now()->addDays(2)->format('Y-m-d') }}" min="{{ now()->addDay()->format('Y-m-d') }}" class="w-full bg-transparent text-sm font-semibold text-gray-800 focus:outline-none cursor-pointer">
                </div>

                <!-- Guests -->
                <div class="sm:col-span-2 bg-gray-50/90 rounded-2xl p-3 border border-gray-200/80 hover:border-accent transition-colors">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-users text-accent mr-1"></i> Tamu
                    </label>
                    <select name="tamu" class="w-full bg-transparent text-sm font-semibold text-gray-800 focus:outline-none cursor-pointer">
                        <option value="15">10 - 20 Org</option>
                        <option value="35" selected>25 - 40 Org</option>
                        <option value="50">40 - 60 Org</option>
                        <option value="75">50 - 75 Org</option>
                        <option value="100">75 - 125 Org</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2">
                    <button type="submit" class="w-full h-full py-4 px-4 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300 group">
                        <span>Cari Villa</span>
                        <i class="fa-solid fa-arrow-right text-xs text-accent group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="hidden sm:block absolute bottom-0 left-0 right-0 z-10 border-t border-white/10 bg-primary/40 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-around text-center text-white">
            <div>
                <p class="font-display text-2xl font-bold text-accent">14 Unit</p>
                <p class="text-white/60 text-xs uppercase tracking-wider">Kamar Tidur (KM Dalam)</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <p class="font-display text-2xl font-bold text-accent">9 Pilihan</p>
                <p class="text-white/60 text-xs uppercase tracking-wider">Paket Sesuai Kebutuhan</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <p class="font-display text-2xl font-bold text-accent">125 Org</p>
                <p class="text-white/60 text-xs uppercase tracking-wider">Kapasitas Maksimal</p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: KOLEKSI PAKET VILLA POPULER (Nginapsini Bento Grid Style) -->
<section class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-end justify-between mb-16">
            <div>
                <p class="text-accent font-semibold tracking-[0.2em] uppercase text-xs mb-3">Pilihan Terbaik Pacet</p>
                <h2 class="font-display text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-bold text-primary leading-tight">
                    Paket Villa <span class="italic text-primary/40">Favorit</span>
                </h2>
            </div>
            <a href="{{ route('packages.index') }}" class="hidden md:inline-flex items-center gap-2 text-primary text-sm font-medium link-underline hover:text-accent transition-colors">
                Lihat Semua
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        @php
            $pkgList = $featuredPackages->values();
            $pkg0 = $pkgList->get(0);
            $pkg1 = $pkgList->get(1);
            $pkg2 = $pkgList->get(2);

            $badges = [
                'Kolam Renang', 'Aula + Sound', 'Rombongan', 'Keluarga', 'Gathering', 'Full Villa'
            ];

            $getLabel = function($pkg) {
                $name = strtolower($pkg->nama);
                if (str_contains($name, 'full') || str_contains($name, 'royalt')) return 'Full Villa';
                if (str_contains($name, 'aula') || str_contains($name, 'gathering')) return 'Aula + Sound';
                if (str_contains($name, 'big') || str_contains($name, 'group')) return 'Rombongan';
                return 'Kolam Renang';
            };

            $fallbackImg = 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80';
        @endphp

        @if($pkg0 && $pkg1)
        <!-- Row 1: Large Left (7) + Medium Right (5) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">
            <!-- Card Besar (kiri) -->
            <div class="lg:col-span-7">
                <a href="{{ route('packages.show', $pkg0->slug) }}" class="group relative rounded-3xl overflow-hidden min-h-[480px] cursor-pointer block">
                    <img src="{{ $pkg0->thumbnail ?? $fallbackImg }}" alt="{{ $pkg0->nama }}"
                         loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-5 left-5 z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-4 py-1.5 rounded-full">
                            {{ $getLabel($pkg0) }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent opacity-85 group-hover:opacity-95 transition-opacity duration-500"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-8 z-10">
                        <h3 class="font-display text-2xl sm:text-3xl font-bold text-white mb-2">{{ $pkg0->nama }}</h3>
                        <div class="flex items-center gap-3 text-white/50 text-xs mb-4">
                            <span><i class="fa-solid fa-users mr-1"></i>{{ $pkg0->kapasitas_min }}–{{ $pkg0->kapasitas_max }} Orang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30"></span>
                            <span><i class="fa-solid fa-water-ladder mr-1 text-cyan-400"></i>Kolam Renang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30"></span>
                            <span><i class="fa-solid fa-wifi mr-1"></i>WiFi</span>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-white font-bold text-2xl">
                                {{ $pkg0->formatted_price }}
                                <span class="text-white/40 font-normal text-sm ml-1">/malam</span>
                            </p>
                            <span class="bg-white text-primary px-6 py-2.5 rounded-full text-sm font-bold group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                                Lihat & Pesan
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Medium (kanan) -->
            <div class="lg:col-span-5">
                <a href="{{ route('packages.show', $pkg1->slug) }}" class="group relative rounded-3xl overflow-hidden min-h-[480px] cursor-pointer block">
                    <img src="{{ $pkg1->thumbnail ?? $fallbackImg }}" alt="{{ $pkg1->nama }}"
                         loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-5 left-5 z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-4 py-1.5 rounded-full">
                            {{ $getLabel($pkg1) }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent opacity-85 group-hover:opacity-95 transition-opacity duration-500"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-8 z-10">
                        <h3 class="font-display text-2xl sm:text-3xl font-bold text-white mb-2">{{ $pkg1->nama }}</h3>
                        <div class="flex items-center gap-3 text-white/50 text-xs mb-4">
                            <span><i class="fa-solid fa-users mr-1"></i>{{ $pkg1->kapasitas_min }}–{{ $pkg1->kapasitas_max }} Orang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30"></span>
                            <span><i class="fa-solid fa-water-ladder mr-1 text-cyan-400"></i>Kolam Renang</span>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-white font-bold text-2xl">
                                {{ $pkg1->formatted_price }}
                                <span class="text-white/40 font-normal text-sm ml-1">/malam</span>
                            </p>
                            <span class="bg-white/15 backdrop-blur-sm text-white text-sm font-bold px-5 py-2.5 rounded-full group-hover:bg-white group-hover:text-primary transition-all duration-300 border border-white/20">
                                Detail
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($pkg2)
        <!-- Row 2: Card Medium (kiri) + Explore Card (kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Card ke-3 -->
            <div class="lg:col-span-5">
                <a href="{{ route('packages.show', $pkg2->slug) }}" class="group relative rounded-3xl overflow-hidden min-h-[360px] cursor-pointer block">
                    <img src="{{ $pkg2->thumbnail ?? $fallbackImg }}" alt="{{ $pkg2->nama }}"
                         loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-5 left-5 z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-4 py-1.5 rounded-full">
                            {{ $getLabel($pkg2) }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent opacity-85 group-hover:opacity-95 transition-opacity duration-500"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-7 z-10">
                        <h3 class="font-display text-2xl font-bold text-white mb-2">{{ $pkg2->nama }}</h3>
                        <div class="flex items-center gap-3 text-white/50 text-xs mb-3">
                            <span><i class="fa-solid fa-users mr-1"></i>{{ $pkg2->kapasitas_min }}–{{ $pkg2->kapasitas_max }} Orang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30"></span>
                            <span><i class="fa-solid fa-water-ladder mr-1 text-cyan-400"></i>Kolam Renang</span>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-white font-bold text-xl">
                                {{ $pkg2->formatted_price }}
                                <span class="text-white/40 font-normal text-sm ml-1">/malam</span>
                            </p>
                            <span class="bg-white/15 backdrop-blur-sm text-white text-xs font-bold px-5 py-2.5 rounded-full group-hover:bg-white group-hover:text-primary transition-all duration-300 border border-white/20">
                                Detail
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Explore All Card (kanan, dekorasi) -->
            <div class="lg:col-span-7">
                <a href="{{ route('packages.index') }}" class="group relative rounded-3xl overflow-hidden min-h-[360px] cursor-pointer flex flex-col items-center justify-center bg-primary hover:bg-primary-light transition-all duration-500 border border-white/5">
                    <!-- Decorative pattern -->
                    <div class="hero-pattern absolute inset-0 opacity-30 pointer-events-none"></div>
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl"></div>

                    <div class="relative z-10 text-center px-10">
                        <div class="w-16 h-16 rounded-2xl bg-accent/20 text-accent flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <p class="text-accent font-semibold tracking-[0.2em] uppercase text-xs mb-3">Semua Tersedia</p>
                        <h3 class="font-display text-3xl sm:text-4xl font-bold text-white mb-4">
                            Lihat <span class="italic text-white/40">9 Paket</span><br>Villa Lengkap
                        </h3>
                        <p class="text-white/50 text-sm leading-relaxed mb-8 max-w-xs mx-auto">
                            Dari keluarga kecil hingga rombongan 125 orang — kami punya paket yang tepat untuk Anda.
                        </p>
                        <span class="inline-flex items-center gap-2 bg-accent text-primary px-7 py-3 rounded-full font-bold text-sm group-hover:bg-white group-hover:text-primary transition-colors duration-300 shadow-xl shadow-accent/20">
                            Jelajahi Semua Paket
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- Mobile: Lihat Semua link -->
        <div class="mt-10 text-center md:hidden">
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-primary font-medium text-sm link-underline hover:text-accent transition-colors">
                Lihat Semua 9 Paket Villa
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>

<!-- SECTION: FASILITAS VILLA BUKIT BUNGA -->
<section id="fasilitas" class="py-24 bg-cream relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs uppercase tracking-[0.2em] font-semibold text-accent block mb-2">Fasilitas Lengkap</span>
            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-primary mb-4">
                Segala Kenyamanan <br><span class="italic font-normal text-gray-400">di Satu Tempat</span>
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                Villa Bukit Bunga dirancang khusus untuk memenuhi kenyamanan acara besar maupun keluarga dengan fasilitas terawat dan pemandangan asri pegunungan.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Item 1 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-water-ladder"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Swimming Pool</h3>
                <p class="text-xs text-gray-500">Kolam renang bersih view pegunungan untuk dewasa & anak-anak</p>
            </div>

            <!-- Item 2 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-users-rectangle"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Aula & Sound System</h3>
                <p class="text-xs text-gray-500">Ruang pertemuan serbaguna dengan meja kursi rapat & audio lengkap</p>
            </div>

            <!-- Item 3 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">14 Kamar Tidur</h3>
                <p class="text-xs text-gray-500">9 Kamar Besar, 5 Kamar Kecil dengan Kamar Mandi Dalam</p>
            </div>

            <!-- Item 4 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Dapur Lengkap</h3>
                <p class="text-xs text-gray-500">Peralatan masak, kompor, kulkas, dispenser, dan area makan luas</p>
            </div>

            <!-- Item 5 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Wifi Kencang</h3>
                <p class="text-xs text-gray-500">Koneksi internet stabil untuk streaming dan kerja remote</p>
            </div>

            <!-- Item 6 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-square-parking"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Parkir Bis & Mobil</h3>
                <p class="text-xs text-gray-500">Area parkir luas aman, muat beberapa bis pariwisata dan mobil pribadi</p>
            </div>

            <!-- Item 7 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Area BBQ Malam</h3>
                <p class="text-xs text-gray-500">Panggangan arang outdoor untuk malam keakraban keluarga</p>
            </div>

            <!-- Item 8 -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-hot-tub-person"></i>
                </div>
                <h3 class="font-bold text-primary text-base mb-1">Dekat Pemandian Panas</h3>
                <p class="text-xs text-gray-500">Hanya 3 menit ke Wisata Pemandian Air Panas Alami Padusan Pacet</p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: 4 LANGKAH CARA BOOKING -->
<section id="cara-booking" class="py-24 bg-cream">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16">
            <p class="text-accent font-semibold tracking-[0.2em] uppercase text-xs mb-3">Reservasi Mudah</p>
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-primary">
                Cara Booking Villa
            </h2>
        </div>

        <div class="flex flex-col md:flex-row items-stretch md:items-start gap-0">

            <!-- Step 1 -->
            <div class="flex-1 text-center px-4">
                <div class="w-11 h-11 rounded-full border-2 border-accent text-accent font-semibold text-sm flex items-center justify-center mx-auto mb-5">
                    1
                </div>
                <h3 class="font-semibold text-primary text-base mb-2">Pilih Paket</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Jelajahi pilihan paket villa, tentukan tanggal check-in dan jumlah peserta yang sesuai kebutuhan.
                </p>
            </div>

            <!-- Arrow -->
            <div class="hidden md:flex items-center pt-5 shrink-0">
                <div class="w-8 h-px bg-gray-200"></div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 -ml-1"></i>
            </div>

            <!-- Step 2 -->
            <div class="flex-1 text-center px-4 mt-10 md:mt-0">
                <div class="w-11 h-11 rounded-full border-2 border-accent text-accent font-semibold text-sm flex items-center justify-center mx-auto mb-5">
                    2
                </div>
                <h3 class="font-semibold text-primary text-base mb-2">Login / Daftar Akun</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Masuk ke akun tamu atau daftar gratis. Akun diperlukan untuk memproses dan melacak reservasi Anda.
                </p>
            </div>

            <!-- Arrow -->
            <div class="hidden md:flex items-center pt-5 shrink-0">
                <div class="w-8 h-px bg-gray-200"></div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 -ml-1"></i>
            </div>

            <!-- Step 3 -->
            <div class="flex-1 text-center px-4 mt-10 md:mt-0">
                <div class="w-11 h-11 rounded-full border-2 border-accent text-accent font-semibold text-sm flex items-center justify-center mx-auto mb-5">
                    3
                </div>
                <h3 class="font-semibold text-primary text-base mb-2">Konfirmasi & Bayar</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Isi data tamu dan bayar via QRIS, Virtual Account, atau Transfer Manual. Cukup DP 50%!
                </p>
            </div>

            <!-- Arrow -->
            <div class="hidden md:flex items-center pt-5 shrink-0">
                <div class="w-8 h-px bg-gray-200"></div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 -ml-1"></i>
            </div>

            <!-- Step 4 -->
            <div class="flex-1 text-center px-4 mt-10 md:mt-0">
                <div class="w-11 h-11 rounded-full border-2 border-accent text-accent font-semibold text-sm flex items-center justify-center mx-auto mb-5">
                    4
                </div>
                <h3 class="font-semibold text-primary text-base mb-2">Check In & Nikmati</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Tunjukkan kode booking saat tiba, ambil kunci kamar, dan nikmati suasana sejuk Pacet!
                </p>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-14">
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2.5 bg-primary hover:bg-primary-light text-white px-8 py-3.5 rounded-full font-semibold text-sm transition-all shadow-md hover:shadow-xl">
                <i class="fa-solid fa-calendar-check text-accent text-sm"></i>
                Mulai Pesan Sekarang
            </a>
            <p class="text-xs text-gray-400 mt-3">Gratis daftar akun • Tidak ada biaya tambahan</p>
        </div>

    </div>
</section>
<!-- SECTION: TESTIMONIALS (Nginapsini Style) -->
<section class="py-24 bg-primary text-white relative overflow-hidden hero-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-xs uppercase tracking-[0.2em] font-semibold text-accent block mb-2">Pengalaman Menginap</span>
            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-bold">
                Kata <span class="italic text-accent font-normal">Mereka</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 gap-1 mb-4 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed italic mb-6">
                        "Paket 6 sangat pas buat reuni angkatan kampus kami 50 orang. Semua bisa tidur di kamar tanpa rebutan, kolam renangnya bersih, dan sound system aulanya mantap buat karaoke malam!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <div class="w-10 h-10 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-sm">
                        R
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Rendra Wijaya</h4>
                        <p class="text-xs text-accent">Alumni Unair - Rombongan 50 Org</p>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 gap-1 mb-4 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed italic mb-6">
                        "Bookingnya gampang banget lewat website ini, tinggal pilih paket terus bayar DP via QRIS langsung terkonfirmasi otomatis tanpa nunggu admin balas berjam-jam. Recommended!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <div class="w-10 h-10 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-sm">
                        S
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Siti Nurhaliza</h4>
                        <p class="text-xs text-accent">Family Gathering Surabaya</p>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 gap-1 mb-4 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed italic mb-6">
                        "Sewa Paket 9 untuk LDKS sekolah kami 90 siswa. Villa luas, parkiran bis leluasa masuk, dapur lengkap buat masak tim konsumsi, dan dekat banget sama air panas Pacet. Sukses terus!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <div class="w-10 h-10 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-sm">
                        B
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Budi Setiawan</h4>
                        <p class="text-xs text-accent">Pembina OSIS SMA Sidoarjo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: CALL TO ACTION -->
<section class="py-20 bg-cream">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-primary mb-4">
            Punya Rencana Acara di Pacet?
        </h2>
        <p class="text-gray-600 text-sm max-w-xl mx-auto mb-8">
            Konsultasikan tanggal dan jumlah rombongan Anda langsung dengan kami. Kami siap memberikan penawaran paket terbaik untuk acara spesial Anda.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="https://wa.me/6285808408835?text=Halo%20Villa%20Bukit%20Bunga%20Pacet%2C%20saya%20mau%20konsultasi%20paket%20villa" 
               target="_blank" 
               class="inline-flex items-center gap-2.5 px-8 py-4 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-xl hover:shadow-2xl transition-all">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span>Hubungi via WhatsApp</span>
            </a>
            <a href="{{ route('packages.index') }}" 
               class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-lg transition-all">
                <span>Pilih & Booking Sekarang</span>
                <i class="fa-solid fa-arrow-right text-xs text-accent"></i>
            </a>
        </div>
    </div>
</section>
@endsection
