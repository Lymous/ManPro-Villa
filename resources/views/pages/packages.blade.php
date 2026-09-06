@extends('layouts.app')

@section('title', 'Pilihan Paket Villa Bukit Bunga Pacet - Kapasitas 10 s/d 125 Orang')

@section('content')
<!-- Page Hero — Konsisten dengan Home -->
<section class="relative luxury-gradient text-white overflow-hidden hero-pattern">
    <!-- Overlay image & gradient -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=80"
             alt="Paket Villa Bukit Bunga Pacet"
             class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay filter brightness-90">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/70 via-primary/85 to-primary/95"></div>
        <!-- Ambient glow -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-accent/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 text-center">

        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-5">
            Daftar Paket Villa <span class="italic text-accent font-normal">&amp; Kamar</span>
        </h1>
        <p class="text-white/70 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
            Mulai dari paket keluarga kecil hemat <strong class="text-accent">Rp 500.000</strong> hingga sewa privat seluruh villa 14 kamar untuk <strong class="text-accent">125 orang</strong>.
        </p>

        <!-- Quick stats -->
        <div class="mt-10 inline-flex flex-wrap items-center justify-center gap-6 bg-white/8 backdrop-blur-md border border-white/15 rounded-2xl px-8 py-4">
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-white">9</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Pilihan Paket</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-white">6–125</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Kapasitas Tamu</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-white">3</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Kolam Renang</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-accent">Free</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Parkir Bus</p>
            </div>
        </div>
    </div>
</section>


<!-- Main Catalog Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/80 shadow-sm mb-10">
        <form action="{{ route('packages.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('packages.index') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold {{ !request('kategori') && !request('tamu') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Semua Paket
                </a>
                <a href="{{ route('packages.index', ['tamu' => 18]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold {{ request('tamu') == 18 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Keluarga (10–20 Org)
                </a>
                <a href="{{ route('packages.index', ['tamu' => 45]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold {{ request('tamu') == 45 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Medium (25–50 Org)
                </a>
                <a href="{{ route('packages.index', ['tamu' => 75]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold {{ request('tamu') == 75 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Akbar (50–125 Org)
                </a>
            </div>

            <div class="text-xs text-gray-500 font-medium">
                Menampilkan <span class="font-bold text-primary">{{ $packages->count() }}</span> opsi akomodasi
            </div>
        </form>
    </div>

    <!-- Package Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($packages as $pkg)
        <div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 flex flex-col justify-between">
            <div class="relative h-64 overflow-hidden bg-gray-100">
                <img src="{{ $pkg->thumbnail ?? 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80' }}" 
                     alt="{{ $pkg->nama }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    <span class="bg-primary/80 backdrop-blur-md text-white text-[11px] font-semibold px-3 py-1 rounded-full border border-white/20">
                        <i class="fa-solid fa-users text-accent mr-1"></i> {{ $pkg->kapasitas_min }}–{{ $pkg->kapasitas_max }} Orang
                    </span>
                    @if(str_contains(strtolower($pkg->nama), 'aula'))
                    <span class="bg-accent/90 backdrop-blur-md text-primary text-[11px] font-bold px-3 py-1 rounded-full">
                        <i class="fa-solid fa-champagne-glasses mr-1"></i> Aula + Sound
                    </span>
                    @endif
                </div>

                <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md text-white text-xs px-3 py-1 rounded-full">
                    <i class="fa-solid fa-water-ladder text-cyan-400 mr-1"></i> Kolam Renang
                </div>
            </div>

            <div class="p-6 flex flex-col flex-grow justify-between">
                <div>
                    <h3 class="font-display text-xl font-bold text-primary group-hover:text-accent transition-colors line-clamp-2 mb-2">
                        {{ $pkg->nama }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-3 mb-4">
                        {{ $pkg->deskripsi }}
                    </p>

                    <!-- Facilities -->
                    <div class="flex flex-wrap gap-1.5 mb-6">
                        @if($pkg->fasilitas && is_array($pkg->fasilitas))
                            @foreach(array_slice($pkg->fasilitas, 0, 4) as $fas)
                            <span class="text-[11px] bg-gray-50 text-gray-600 px-2.5 py-1 rounded-lg border border-gray-100">
                                • {{ $fas }}
                            </span>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Price and Action -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold block">Harga Mulai</span>
                        <div class="flex items-baseline gap-1">
                            <span class="font-bold text-xl text-primary">{{ $pkg->formatted_price }}</span>
                            <span class="text-xs text-gray-400">/malam</span>
                        </div>
                    </div>

                    <a href="{{ route('packages.show', $pkg->slug) }}" 
                       class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-primary hover:bg-accent text-white hover:text-primary text-xs font-semibold transition-all duration-300 shadow-sm">
                        <span>Lihat Detail</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-16 text-center text-gray-500">
            <i class="fa-solid fa-hotel text-4xl text-gray-300 mb-3 block"></i>
            <p>Tidak ada paket yang sesuai dengan kriteria pencarian Anda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
