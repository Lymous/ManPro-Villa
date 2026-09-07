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
        <!-- Tagline Lokasi -->
        <div class="inline-flex items-center justify-center gap-2 text-xs sm:text-sm font-medium tracking-wider uppercase text-white/90 mb-4 sm:mb-5">
            <i class="fa-solid fa-location-dot text-accent text-xs"></i>
            <span>Pacet, Mojokerto</span>
            <span class="text-accent/60 mx-0.5">•</span>
            <span class="text-white/70">Jawa Timur</span>
        </div>

        <!-- Headline Besar -->
        <h1 class="font-display text-3xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-bold tracking-tight leading-[1.1] mb-5">
            Complete Your <br>
            <span class="italic text-accent font-normal">Every Moment</span>
        </h1>

        <p class="text-white/85 text-sm sm:text-base md:text-lg max-w-3xl mx-auto leading-relaxed mb-8 sm:mb-10 font-normal">
            Villa Keluarga & Rombongan Gathering di Pacet. Cocok untuk Reuni, Acara Sekolah, Kampus, LDKS, dan Komunitas dengan Kolam Renang & Aula Sound System.
        </p>        <!-- Quick Booking Search Bar (Reference Match: Luxury Glassmorphic Search & Filter) -->
        <div class="bg-black/40 backdrop-blur-2xl border border-white/20 rounded-3xl p-3.5 sm:p-5 md:p-6 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] max-w-5xl mx-auto text-left text-white ring-1 ring-white/10 transition-all duration-300">
            <form action="{{ route('packages.index') }}" method="GET" id="main-booking-form">
                
                <!-- TOP ROW: Check-in, Check-out, Tamu, Cari (Ukuran Seragam, Presisi & Rapi di Mobile & Desktop) -->
                <div class="flex flex-col sm:flex-row items-stretch gap-2.5 sm:gap-3">
                    <!-- Check-in (Tinggi 52px Presisi & Tanpa Mengkerut di Mobile) -->
                    <div class="w-full sm:flex-1 sm:min-w-0 h-[52px] shrink-0 relative bg-white/10 hover:bg-white/15 focus-within:bg-white/20 border border-white/20 hover:border-white/30 rounded-2xl px-4 flex items-center gap-3 transition-colors cursor-pointer group" onclick="triggerDatePicker('ref-check-in')">
                        <i class="fa-regular fa-calendar text-white/60 group-hover:text-accent transition-colors text-base shrink-0"></i>
                        <span id="ref-check-in-label" class="text-sm font-medium text-white/90 block truncate select-none">
                            {{ request('check_in') ? \Carbon\Carbon::parse(request('check_in'))->translatedFormat('d M Y') : 'Check-in' }}
                        </span>
                        <input type="date" 
                               name="check_in" 
                               id="ref-check-in" 
                               value="{{ request('check_in') }}" 
                               min="{{ now()->format('Y-m-d') }}" 
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                    </div>

                    <!-- Check-out (Tinggi 52px Presisi & Tanpa Mengkerut di Mobile) -->
                    <div class="w-full sm:flex-1 sm:min-w-0 h-[52px] shrink-0 relative bg-white/10 hover:bg-white/15 focus-within:bg-white/20 border border-white/20 hover:border-white/30 rounded-2xl px-4 flex items-center gap-3 transition-colors cursor-pointer group" onclick="triggerDatePicker('ref-check-out')">
                        <i class="fa-regular fa-calendar text-white/60 group-hover:text-accent transition-colors text-base shrink-0"></i>
                        <span id="ref-check-out-label" class="text-sm font-medium text-white/90 block truncate select-none">
                            {{ request('check_out') ? \Carbon\Carbon::parse(request('check_out'))->translatedFormat('d M Y') : 'Check-out' }}
                        </span>
                        <input type="date" 
                               name="check_out" 
                               id="ref-check-out" 
                               value="{{ request('check_out') }}" 
                               min="{{ now()->addDay()->format('Y-m-d') }}" 
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                    </div>

                    <!-- Tamu (Tinggi 52px Presisi Sama dengan Check-in & Check-out) -->
                    <div class="w-full sm:flex-1 sm:min-w-0 h-[52px] shrink-0 relative" id="tamu-container">
                        <button type="button" 
                                id="tamu-trigger-btn"
                                onclick="toggleTamuPopover(event)"
                                class="w-full h-full bg-white/10 hover:bg-white/15 focus:bg-white/20 border border-white/20 hover:border-white/30 rounded-2xl px-4 flex items-center gap-3 transition-colors text-left cursor-pointer group">
                            <i class="fa-regular fa-user text-white/60 group-hover:text-accent transition-colors text-base shrink-0"></i>
                            <span id="bar-tamu-display" class="text-sm font-medium text-white/95 block truncate select-none">
                                {{ request('tamu', 1) }} Tamu
                            </span>
                        </button>

                        <!-- Dropdown Popover Jumlah Tamu (Persis Sesuai Gambar ke-2) -->
                        <div id="tamu-popover" class="hidden absolute left-0 right-0 sm:left-auto sm:right-0 sm:w-72 top-full mt-2 bg-[#081716]/95 backdrop-blur-2xl border border-white/20 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.7)] p-4 sm:p-5 z-50 transition-all duration-200">
                            <span class="block text-[11px] uppercase tracking-wider font-bold text-white/60 mb-3 select-none">JUMLAH TAMU</span>
                            
                            <div class="flex items-center justify-between gap-3">
                                <!-- Minus Button -->
                                <button type="button" 
                                        onclick="adjustRefGuests(-1, event)" 
                                        class="w-12 h-12 rounded-xl bg-white/5 hover:bg-white/15 border border-white/15 active:scale-95 text-white/80 hover:text-white flex items-center justify-center transition-all cursor-pointer select-none"
                                        title="Kurangi 1 tamu">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>

                                <!-- Number Input (Tampilan Serif Besar Sesuai Gambar 2 + Bebas Ketik Langsung) -->
                                <div class="flex-1 flex items-center justify-center">
                                    <input type="number" 
                                           name="tamu" 
                                           id="ref-tamu" 
                                           value="{{ request('tamu', 1) }}" 
                                           min="1" 
                                           max="150" 
                                           class="w-16 bg-transparent text-3xl font-bold font-serif text-white text-center focus:outline-none border-b border-transparent focus:border-accent/60 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none transition-colors"
                                           oninput="onTamuInputChange(this.value)">
                                </div>

                                <!-- Plus Button -->
                                <button type="button" 
                                        onclick="adjustRefGuests(1, event)" 
                                        class="w-12 h-12 rounded-xl bg-white/5 hover:bg-white/15 border border-white/15 active:scale-95 text-white/80 hover:text-white flex items-center justify-center transition-all cursor-pointer select-none"
                                        title="Tambah 1 tamu">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            <!-- Footer Info & Selesai -->
                            <div class="mt-3.5 pt-2.5 border-t border-white/10 flex items-center justify-between text-[11px] text-white/50 select-none">
                                <span>Bisa ketik angka langsung</span>
                                <button type="button" 
                                        onclick="closeTamuPopover(event)" 
                                        class="text-accent hover:text-accent-light font-semibold cursor-pointer transition-colors">
                                    Selesai
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Cari (Tinggi 52px Presisi Sama Sempurna) -->
                    <div class="w-full sm:w-28 md:w-32 lg:w-36 h-[52px] shrink-0">
                        <button type="submit" 
                                class="w-full h-full rounded-2xl bg-[#C8A44E] hover:bg-[#b8943f] text-white font-bold text-sm shadow-lg hover:shadow-xl hover:shadow-[#C8A44E]/20 transition-all duration-300 active:scale-95 flex items-center justify-center">
                            <span>Cari</span>
                        </button>
                    </div>
                </div>

                @php
                    $hasActiveFilter = request()->filled('min_price') || request()->filled('max_price') || request()->filled('min_kamar') || request()->filled('sort') || (request()->filled('fasilitas') && request('fasilitas') !== 'semua');
                @endphp

                <!-- FILTER TOGGLE BUTTON (Persis Sesuai Posisi pada Referensi) -->
                <div class="pt-3 flex items-center justify-between">
                    <button type="button" 
                            id="btn-toggle-filter"
                            onclick="toggleFilterPanel(event)" 
                            class="bg-white/10 hover:bg-white/20 border border-white/20 hover:border-white/30 rounded-xl px-3.5 py-1.5 text-xs font-semibold text-white/90 inline-flex items-center gap-2 transition-all active:scale-95 cursor-pointer {{ $hasActiveFilter ? 'bg-white/25 border-accent/60' : '' }}">
                        <i class="fa-solid fa-sliders text-accent text-xs"></i>
                        <span>Filter</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-white/60 transition-transform duration-300 {{ $hasActiveFilter ? 'rotate-180' : '' }}" id="filter-chevron"></i>
                    </button>

                    <div class="text-[11px] text-white/60 hidden sm:block">
                        <i class="fa-solid fa-shield-halved text-accent mr-1"></i> Kapasitas Fleksibel 6 - 125 Orang
                    </div>
                </div>


                <!-- EXPANDED FILTER PANEL (Sesuai Referensi Pengguna) -->
                <div id="filter-panel" class="pt-5 border-t border-white/15 mt-4 space-y-5 transition-all duration-300 {{ $hasActiveFilter ? '' : 'hidden' }}">
                    
                    <!-- Harga per malam Slider (Interactive Dual Range Slider) -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-2.5">
                            <span class="text-white/80 font-medium">Harga per malam</span>
                            <span id="price-slider-label" class="font-mono font-semibold text-accent text-xs sm:text-sm">
                                Rp 0 – Rp 3.500.000+
                            </span>
                        </div>
                        <div class="relative h-6 flex items-center select-none" id="price-slider-container">
                            <!-- Background Track Line -->
                            <div class="absolute inset-x-0 h-[4px] bg-white/20 rounded-full"></div>
                            <!-- Active Highlight Track (Gold between Min and Max) -->
                            <div id="slider-highlight" class="absolute h-[4px] bg-[#C8A44E] rounded-full" style="left: 0%; right: 0%;"></div>

                            <!-- Min Price Range Input -->
                            <input type="range" 
                                   id="ref-price-min" 
                                   name="min_price" 
                                   min="0" 
                                   max="3500000" 
                                   step="50000" 
                                   value="{{ request('min_price', 0) }}"
                                   oninput="onMinPriceInput(this.value)"
                                   class="dual-range-input absolute inset-x-0 w-full z-20">

                            <!-- Max Price Range Input -->
                            <input type="range" 
                                   id="ref-price-max" 
                                   name="max_price" 
                                   min="0" 
                                   max="3500000" 
                                   step="50000" 
                                   value="{{ request('max_price', 3500000) }}"
                                   oninput="onMaxPriceInput(this.value)"
                                   class="dual-range-input absolute inset-x-0 w-full z-30">
                        </div>
                    </div>

                    <!-- Filter Dropdowns Row (Min. Kamar Tidur, Urutkan, Fasilitas) - 3 Kolom Seimbang Sempurna -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <!-- Min. kamar tidur Dropdown -->
                        <div class="relative custom-dropdown" data-name="min_kamar">
                            <label class="block text-[11px] text-white/70 font-medium mb-1.5">Min. kamar tidur</label>
                            <input type="hidden" name="min_kamar" id="input-min_kamar" value="{{ request('min_kamar', '') }}">
                            <button type="button" 
                                    class="w-full bg-white/10 hover:bg-white/15 focus:bg-white/20 border border-white/20 focus:border-accent text-white text-xs rounded-xl px-3.5 py-2.5 flex items-center justify-between transition-colors text-left cursor-pointer"
                                    onclick="toggleCustomDropdown(this, event)">
                                <span class="dropdown-label truncate select-none">
                                    @if(request('min_kamar') == 1)
                                        1 Kamar
                                    @elseif(request('min_kamar') == 3)
                                        Min. 3 Kamar Tidur
                                    @elseif(request('min_kamar') == 5)
                                        Min. 5 Kamar Tidur
                                    @elseif(request('min_kamar') == 10)
                                        10 - 14 Kamar (Seluruh Villa)
                                    @else
                                        Semua
                                    @endif
                                </span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-white/60 transition-transform duration-200 dropdown-chevron shrink-0 ml-2 pointer-events-none"></i>
                            </button>
                            <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-[#081716]/95 backdrop-blur-2xl border border-white/20 rounded-xl shadow-2xl p-1.5 z-50 space-y-1">
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('min_kamar', '', 'Semua', this)">
                                    <span>Semua</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ !request('min_kamar') ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('min_kamar', '1', '1 Kamar', this)">
                                    <span>1 Kamar</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('min_kamar') == 1 ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('min_kamar', '3', 'Min. 3 Kamar Tidur', this)">
                                    <span>Min. 3 Kamar Tidur</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('min_kamar') == 3 ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('min_kamar', '5', 'Min. 5 Kamar Tidur', this)">
                                    <span>Min. 5 Kamar Tidur</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('min_kamar') == 5 ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('min_kamar', '10', '10 - 14 Kamar (Seluruh Villa)', this)">
                                    <span>10 - 14 Kamar (Seluruh Villa)</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('min_kamar') == 10 ? '' : 'hidden' }} checkmark"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Urutkan Dropdown -->
                        <div class="relative custom-dropdown" data-name="sort">
                            <label class="block text-[11px] text-white/70 font-medium mb-1.5">Urutkan</label>
                            <input type="hidden" name="sort" id="input-sort" value="{{ request('sort', '') }}">
                            <button type="button" 
                                    class="w-full bg-white/10 hover:bg-white/15 focus:bg-white/20 border border-white/20 focus:border-accent text-white text-xs rounded-xl px-3.5 py-2.5 flex items-center justify-between transition-colors text-left cursor-pointer"
                                    onclick="toggleCustomDropdown(this, event)">
                                <span class="dropdown-label truncate select-none">
                                    @if(request('sort') == 'harga_rendah')
                                        Harga Terendah
                                    @elseif(request('sort') == 'harga_tinggi')
                                        Harga Tertinggi
                                    @elseif(request('sort') == 'kapasitas')
                                        Kapasitas Terbesar
                                    @else
                                        Relevansi
                                    @endif
                                </span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-white/60 transition-transform duration-200 dropdown-chevron shrink-0 ml-2 pointer-events-none"></i>
                            </button>
                            <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-[#081716]/95 backdrop-blur-2xl border border-white/20 rounded-xl shadow-2xl p-1.5 z-50 space-y-1">
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('sort', '', 'Relevansi', this)">
                                    <span>Relevansi</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ !request('sort') ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('sort', 'harga_rendah', 'Harga Terendah', this)">
                                    <span>Harga Terendah</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('sort') == 'harga_rendah' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('sort', 'harga_tinggi', 'Harga Tertinggi', this)">
                                    <span>Harga Tertinggi</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('sort') == 'harga_tinggi' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('sort', 'kapasitas', 'Kapasitas Terbesar', this)">
                                    <span>Kapasitas Terbesar</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('sort') == 'kapasitas' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Fasilitas Dropdown -->
                        <div class="relative custom-dropdown" data-name="fasilitas">
                            <label class="block text-[11px] text-white/70 font-medium mb-1.5">Fasilitas</label>
                            <input type="hidden" name="fasilitas" id="input-fasilitas" value="{{ request('fasilitas', 'semua') }}">
                            <button type="button" 
                                    class="w-full bg-white/10 hover:bg-white/15 focus:bg-white/20 border border-white/20 focus:border-accent text-white text-xs rounded-xl px-3.5 py-2.5 flex items-center justify-between transition-colors text-left cursor-pointer"
                                    onclick="toggleCustomDropdown(this, event)">
                                <span class="dropdown-label truncate select-none">
                                    @if(request('fasilitas') == 'kolam')
                                        Kolam Renang
                                    @elseif(request('fasilitas') == 'aula')
                                        Aula & Sound System
                                    @elseif(request('fasilitas') == 'dapur')
                                        Dapur Bersama & BBQ
                                    @elseif(request('fasilitas') == 'parkir')
                                        Parkir Luas Muat Bus
                                    @else
                                        Semua fasilitas
                                    @endif
                                </span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-white/60 transition-transform duration-200 dropdown-chevron shrink-0 ml-2 pointer-events-none"></i>
                            </button>
                            <div class="dropdown-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-[#081716]/95 backdrop-blur-2xl border border-white/20 rounded-xl shadow-2xl p-1.5 z-50 space-y-1">
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('fasilitas', 'semua', 'Semua fasilitas', this)">
                                    <span>Semua fasilitas</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ !request('fasilitas') || request('fasilitas') == 'semua' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('fasilitas', 'kolam', 'Kolam Renang', this)">
                                    <span>Kolam Renang</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('fasilitas') == 'kolam' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('fasilitas', 'aula', 'Aula & Sound System', this)">
                                    <span>Aula & Sound System</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('fasilitas') == 'aula' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('fasilitas', 'dapur', 'Dapur Bersama & BBQ', this)">
                                    <span>Dapur Bersama & BBQ</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('fasilitas') == 'dapur' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                                <div class="dropdown-item px-3 py-2 text-xs rounded-lg hover:bg-white/15 text-white/90 hover:text-white cursor-pointer transition-colors flex items-center justify-between" onclick="selectDropdownOption('fasilitas', 'parkir', 'Parkir Luas Muat Bus', this)">
                                    <span>Parkir Luas Muat Bus</span>
                                    <i class="fa-solid fa-check text-accent text-[10px] {{ request('fasilitas') == 'parkir' ? '' : 'hidden' }} checkmark"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons: Terapkan Filter & Reset -->
                    <div class="pt-2 flex items-center gap-4">
                        <button type="submit" 
                                class="bg-[#C8A44E] hover:bg-[#b8943f] text-[#0F2625] font-bold text-xs rounded-xl px-6 py-2.5 shadow-md hover:shadow-lg transition-all active:scale-95">
                            Terapkan filter
                        </button>
                        <a href="{{ route('home') }}" 
                           class="text-xs text-white/70 hover:text-white transition-colors cursor-pointer">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="hidden sm:block absolute bottom-0 left-0 right-0 z-10 border-t border-white/10 bg-primary/40 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-around text-center text-white">
            <div>
                <p class="font-display text-xl sm:text-2xl font-bold text-accent">14 Unit</p>
                <p class="text-white/60 text-[10px] sm:text-xs uppercase tracking-wider">Kamar Tidur (KM Dalam)</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <p class="font-display text-xl sm:text-2xl font-bold text-accent">9 Pilihan</p>
                <p class="text-white/60 text-[10px] sm:text-xs uppercase tracking-wider">Paket Sesuai Kebutuhan</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <p class="font-display text-xl sm:text-2xl font-bold text-accent">125 Org</p>
                <p class="text-white/60 text-[10px] sm:text-xs uppercase tracking-wider">Kapasitas Maksimal</p>
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
                <a href="{{ route('packages.show', $pkg0->slug) }}" class="group relative rounded-3xl overflow-hidden min-h-[380px] sm:min-h-[480px] cursor-pointer block">
                    <img src="{{ $pkg0->thumbnail ?? $fallbackImg }}" alt="{{ $pkg0->nama }}"
                         loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-4 sm:top-5 left-4 sm:left-5 z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-3.5 sm:px-4 py-1.5 rounded-full">
                            {{ $getLabel($pkg0) }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 z-10">
                        <h3 class="font-display text-xl sm:text-3xl font-bold text-white mb-2 leading-snug">{{ $pkg0->nama }}</h3>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-white/70 text-xs mb-4">
                            <span><i class="fa-solid fa-users mr-1"></i>{{ $pkg0->kapasitas_min }}–{{ $pkg0->kapasitas_max }} Orang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30 hidden sm:inline-block"></span>
                            <span><i class="fa-solid fa-water-ladder mr-1 text-cyan-400"></i>Kolam Renang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30 hidden sm:inline-block"></span>
                            <span><i class="fa-solid fa-wifi mr-1"></i>WiFi</span>
                        </div>
                        <div class="flex flex-wrap sm:flex-nowrap items-end justify-between gap-3">
                            <p class="text-white font-bold text-xl sm:text-2xl">
                                {{ $pkg0->formatted_price }}
                                <span class="text-white/60 font-normal text-xs sm:text-sm ml-1">/malam</span>
                            </p>
                            <span class="bg-white text-primary px-5 sm:px-6 py-2 sm:py-2.5 rounded-full text-xs sm:text-sm font-bold group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                                Lihat & Pesan
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Medium (kanan) -->
            <div class="lg:col-span-5">
                <a href="{{ route('packages.show', $pkg1->slug) }}" class="group relative rounded-3xl overflow-hidden min-h-[380px] sm:min-h-[480px] cursor-pointer block">
                    <img src="{{ $pkg1->thumbnail ?? $fallbackImg }}" alt="{{ $pkg1->nama }}"
                         loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-4 sm:top-5 left-4 sm:left-5 z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-3.5 sm:px-4 py-1.5 rounded-full">
                            {{ $getLabel($pkg1) }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 z-10">
                        <h3 class="font-display text-xl sm:text-3xl font-bold text-white mb-2 leading-snug">{{ $pkg1->nama }}</h3>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-white/70 text-xs mb-4">
                            <span><i class="fa-solid fa-users mr-1"></i>{{ $pkg1->kapasitas_min }}–{{ $pkg1->kapasitas_max }} Orang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30 hidden sm:inline-block"></span>
                            <span><i class="fa-solid fa-water-ladder mr-1 text-cyan-400"></i>Kolam Renang</span>
                        </div>
                        <div class="flex flex-wrap sm:flex-nowrap items-end justify-between gap-3">
                            <p class="text-white font-bold text-xl sm:text-2xl">
                                {{ $pkg1->formatted_price }}
                                <span class="text-white/60 font-normal text-xs sm:text-sm ml-1">/malam</span>
                            </p>
                            <span class="bg-white/15 backdrop-blur-sm text-white text-xs sm:text-sm font-bold px-4 sm:px-5 py-2 sm:py-2.5 rounded-full group-hover:bg-white group-hover:text-primary transition-all duration-300 border border-white/20">
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
                <a href="{{ route('packages.show', $pkg2->slug) }}" class="group relative rounded-3xl overflow-hidden min-h-[320px] sm:min-h-[360px] cursor-pointer block">
                    <img src="{{ $pkg2->thumbnail ?? $fallbackImg }}" alt="{{ $pkg2->nama }}"
                         loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-4 sm:top-5 left-4 sm:left-5 z-10">
                        <span class="bg-accent text-primary text-xs font-bold px-3.5 sm:px-4 py-1.5 rounded-full">
                            {{ $getLabel($pkg2) }}
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90 group-hover:opacity-95 transition-opacity duration-500"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-7 z-10">
                        <h3 class="font-display text-lg sm:text-2xl font-bold text-white mb-2 leading-snug">{{ $pkg2->nama }}</h3>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-white/70 text-xs mb-3">
                            <span><i class="fa-solid fa-users mr-1"></i>{{ $pkg2->kapasitas_min }}–{{ $pkg2->kapasitas_max }} Orang</span>
                            <span class="w-1 h-1 rounded-full bg-white/30 hidden sm:inline-block"></span>
                            <span><i class="fa-solid fa-water-ladder mr-1 text-cyan-400"></i>Kolam Renang</span>
                        </div>
                        <div class="flex flex-wrap sm:flex-nowrap items-end justify-between gap-3">
                            <p class="text-white font-bold text-lg sm:text-xl">
                                {{ $pkg2->formatted_price }}
                                <span class="text-white/60 font-normal text-xs sm:text-sm ml-1">/malam</span>
                            </p>
                            <span class="bg-white/15 backdrop-blur-sm text-white text-xs font-bold px-4 sm:px-5 py-2 sm:py-2.5 rounded-full group-hover:bg-white group-hover:text-primary transition-all duration-300 border border-white/20">
                                Detail
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Explore All Card (kanan, dekorasi) -->
            <div class="lg:col-span-7">
                <a href="{{ route('packages.index') }}" class="group relative rounded-3xl overflow-hidden min-h-[320px] sm:min-h-[360px] cursor-pointer flex flex-col items-center justify-center bg-primary hover:bg-primary-light transition-all duration-500 border border-white/5 p-6 sm:p-10">
                    <!-- Decorative pattern -->
                    <div class="hero-pattern absolute inset-0 opacity-30 pointer-events-none"></div>
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl"></div>

                    <div class="relative z-10 text-center px-4 sm:px-10">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-accent/20 text-accent flex items-center justify-center text-2xl sm:text-3xl mx-auto mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <p class="text-accent font-semibold tracking-[0.2em] uppercase text-xs mb-2 sm:mb-3">Semua Tersedia</p>
                        <h3 class="font-display text-2xl sm:text-4xl font-bold text-white mb-3 sm:mb-4">
                            Lihat <span class="italic text-white/40">9 Paket</span><br>Villa Lengkap
                        </h3>
                        <p class="text-white/50 text-xs sm:text-sm leading-relaxed mb-6 sm:mb-8 max-w-xs mx-auto">
                            Dari keluarga kecil hingga rombongan 125 orang — kami punya paket yang tepat untuk Anda.
                        </p>
                        <span class="inline-flex items-center gap-2 bg-accent text-primary px-5 sm:px-7 py-2.5 sm:py-3 rounded-full font-bold text-xs sm:text-sm group-hover:bg-white group-hover:text-primary transition-colors duration-300 shadow-xl shadow-accent/20">
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
<section id="fasilitas" class="py-16 sm:py-24 bg-cream relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16">
            <span class="text-xs uppercase tracking-[0.2em] font-semibold text-accent block mb-2">Fasilitas Lengkap</span>
            <h2 class="font-display text-2xl sm:text-4xl md:text-5xl font-bold text-primary mb-3 sm:mb-4">
                Segala Kenyamanan <br><span class="italic font-normal text-gray-400">di Satu Tempat</span>
            </h2>
            <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                Villa Bukit Bunga dirancang khusus untuk memenuhi kenyamanan acara besar maupun keluarga dengan fasilitas terawat dan pemandangan asri pegunungan.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-6">
            <!-- Item 1 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-water-ladder"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Swimming Pool</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Kolam renang bersih view pegunungan untuk dewasa & anak-anak</p>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-users-rectangle"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Aula & Sound System</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Ruang pertemuan serbaguna dengan meja kursi rapat & audio lengkap</p>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">14 Kamar Tidur</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">9 Kamar Besar, 5 Kamar Kecil dengan Kamar Mandi Dalam</p>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Dapur Lengkap</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Peralatan masak, kompor, kulkas, dispenser, dan area makan luas</p>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Wifi Kencang</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Koneksi internet stabil untuk streaming dan kerja remote</p>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-square-parking"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Parkir Bis & Mobil</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Area parkir luas aman, muat beberapa bis pariwisata dan mobil pribadi</p>
                </div>
            </div>

            <!-- Item 7 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-fire-burner"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Area BBQ Malam</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Panggangan arang outdoor untuk malam keakraban keluarga</p>
                </div>
            </div>

            <!-- Item 8 -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-lg transition-all duration-300 text-center flex flex-col justify-between">
                <div>
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl sm:text-2xl mx-auto mb-3 sm:mb-4">
                        <i class="fa-solid fa-hot-tub-person"></i>
                    </div>
                    <h3 class="font-bold text-primary text-sm sm:text-base mb-1">Dekat Pemandian Panas</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 leading-snug">Hanya 3 menit ke Wisata Pemandian Air Panas Alami Padusan Pacet</p>
                </div>
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
<section class="py-16 sm:py-24 bg-primary text-white relative overflow-hidden hero-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 sm:mb-16">
            <span class="text-xs uppercase tracking-[0.2em] font-semibold text-accent block mb-2">Pengalaman Menginap</span>
            <h2 class="font-display text-2xl sm:text-4xl md:text-5xl font-bold">
                Kata <span class="italic text-accent font-normal">Mereka</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            <!-- Review 1 -->
            <div class="bg-white/5 backdrop-blur-md p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-white/10 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 gap-1 mb-4 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 text-xs sm:text-sm leading-relaxed italic mb-6">
                        "Paket 6 sangat pas buat reuni angkatan kampus kami 50 orang. Semua bisa tidur di kamar tanpa rebutan, kolam renangnya bersih, dan sound system aulanya mantap buat karaoke malam!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-sm shrink-0">
                        R
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Rendra Wijaya</h4>
                        <p class="text-xs text-accent">Alumni Unair - Rombongan 50 Org</p>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white/5 backdrop-blur-md p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-white/10 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 gap-1 mb-4 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 text-xs sm:text-sm leading-relaxed italic mb-6">
                        "Bookingnya gampang banget lewat website ini, tinggal pilih paket terus bayar DP via QRIS langsung terkonfirmasi otomatis tanpa nunggu admin balas berjam-jam. Recommended!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-sm shrink-0">
                        S
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">Siti Nurhaliza</h4>
                        <p class="text-xs text-accent">Family Gathering Surabaya</p>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white/5 backdrop-blur-md p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-white/10 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 gap-1 mb-4 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-white/80 text-xs sm:text-sm leading-relaxed italic mb-6">
                        "Sewa Paket 9 untuk LDKS sekolah kami 90 siswa. Villa luas, parkiran bis leluasa masuk, dapur lengkap buat masak tim konsumsi, dan dekat banget sama air panas Pacet. Sukses terus!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-sm shrink-0">
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
<section class="py-16 sm:py-20 bg-cream">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="font-display text-2xl sm:text-4xl font-bold text-primary mb-3 sm:mb-4">
            Punya Rencana Acara di Pacet?
        </h2>
        <p class="text-gray-600 text-xs sm:text-sm max-w-xl mx-auto mb-6 sm:mb-8">
            Konsultasikan tanggal dan jumlah rombongan Anda langsung dengan kami. Kami siap memberikan penawaran paket terbaik untuk acara spesial Anda.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="https://wa.me/6285808408835?text=Halo%20Villa%20Bukit%20Bunga%20Pacet%2C%20saya%20mau%20konsultasi%20paket%20villa" 
               target="_blank" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 sm:px-8 py-3.5 sm:py-4 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-lg hover:shadow-xl transition-all">
                <i class="fa-brands fa-whatsapp text-base sm:text-lg"></i>
                <span>Hubungi via WhatsApp</span>
            </a>
            <a href="{{ route('packages.index') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 rounded-full bg-primary hover:bg-primary-light text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all">
                <span>Pilih & Booking Sekarang</span>
                <i class="fa-solid fa-arrow-right text-xs text-accent"></i>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
    .dual-range-input {
        -webkit-appearance: none;
        appearance: none;
        pointer-events: none;
        background: transparent;
        height: 4px;
        outline: none;
    }
    .dual-range-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        pointer-events: auto;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #FAF9F6;
        border: 3.5px solid #C8A44E;
        cursor: grab;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .dual-range-input::-webkit-slider-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 0 12px rgba(200, 164, 78, 0.7);
    }
    .dual-range-input::-webkit-slider-thumb:active {
        cursor: grabbing;
        transform: scale(1.2);
    }
    .dual-range-input::-moz-range-thumb {
        pointer-events: auto;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #FAF9F6;
        border: 3.5px solid #C8A44E;
        cursor: grab;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
    }
    .dual-range-input::-moz-range-track {
        background: transparent;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script>
    const monthsId = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

    function formatIndoDate(dateStr) {
        if (!dateStr) return null;
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return null;
        const day = String(d.getDate()).padStart(2, '0');
        const month = monthsId[d.getMonth()];
        const year = d.getFullYear();
        return `${day} ${month} ${year}`;
    }

    function updateDateDisplay() {
        const checkIn = document.getElementById('ref-check-in');
        const checkOut = document.getElementById('ref-check-out');
        const labelIn = document.getElementById('ref-check-in-label');
        const labelOut = document.getElementById('ref-check-out-label');

        if (checkIn && labelIn) {
            labelIn.textContent = formatIndoDate(checkIn.value) || 'Check-in';
        }
        if (checkOut && labelOut) {
            labelOut.textContent = formatIndoDate(checkOut.value) || 'Check-out';
        }
    }

    function triggerDatePicker(id) {
        const input = document.getElementById(id);
        if (!input) return;
        if (typeof input.showPicker === 'function') {
            try {
                input.showPicker();
            } catch (e) {
                input.focus();
            }
        } else {
            input.focus();
        }
    }

    window.toggleTamuPopover = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const popover = document.getElementById('tamu-popover');
        if (!popover) return;

        // Close custom dropdowns if any
        document.querySelectorAll('.custom-dropdown .dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.custom-dropdown .dropdown-chevron').forEach(c => c.classList.remove('rotate-180'));

        popover.classList.toggle('hidden');
        if (!popover.classList.contains('hidden')) {
            const input = document.getElementById('ref-tamu');
            if (input) {
                setTimeout(() => input.select(), 50);
            }
        }
    };

    window.closeTamuPopover = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const popover = document.getElementById('tamu-popover');
        if (popover) popover.classList.add('hidden');
    };

    window.onTamuInputChange = function(val) {
        let num = parseInt(val, 10);
        const display = document.getElementById('bar-tamu-display');
        if (isNaN(num) || num < 1) {
            if (display) display.textContent = '1 Tamu';
            return;
        }
        if (display) {
            display.textContent = num + ' Tamu';
        }
    };

    window.adjustRefGuests = function(delta, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const input = document.getElementById('ref-tamu');
        if (!input) return;
        let current = parseInt(input.value, 10);
        if (isNaN(current) || current < 1) current = 1;
        current = Math.max(1, Math.min(200, current + delta));
        input.value = current;

        const display = document.getElementById('bar-tamu-display');
        if (display) {
            display.textContent = current + ' Tamu';
        }
    };

    const PRICE_MIN_LIMIT = 0;
    const PRICE_MAX_LIMIT = 3500000;
    const PRICE_MIN_GAP = 100000;

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function updatePriceSliderDisplay() {
        const minInput = document.getElementById('ref-price-min');
        const maxInput = document.getElementById('ref-price-max');
        const label = document.getElementById('price-slider-label');
        const highlight = document.getElementById('slider-highlight');

        if (!minInput || !maxInput) return;

        let minVal = parseInt(minInput.value, 10);
        let maxVal = parseInt(maxInput.value, 10);

        if (minVal > maxVal - PRICE_MIN_GAP) {
            minVal = maxVal - PRICE_MIN_GAP;
            minInput.value = minVal;
        }

        const minPercent = ((minVal - PRICE_MIN_LIMIT) / (PRICE_MAX_LIMIT - PRICE_MIN_LIMIT)) * 100;
        const maxPercent = ((maxVal - PRICE_MIN_LIMIT) / (PRICE_MAX_LIMIT - PRICE_MIN_LIMIT)) * 100;

        if (highlight) {
            highlight.style.left = minPercent + '%';
            highlight.style.right = (100 - maxPercent) + '%';
        }

        if (label) {
            const minStr = minVal <= 0 ? 'Rp 0' : formatRupiah(minVal);
            const maxStr = maxVal >= PRICE_MAX_LIMIT ? 'Rp 3.500.000+' : formatRupiah(maxVal);
            label.textContent = `${minStr} – ${maxStr}`;
        }
    }

    window.onMinPriceInput = function(val) {
        const minInput = document.getElementById('ref-price-min');
        const maxInput = document.getElementById('ref-price-max');
        if (!minInput || !maxInput) return;

        minInput.style.zIndex = '30';
        maxInput.style.zIndex = '20';

        let minVal = parseInt(val, 10);
        let maxVal = parseInt(maxInput.value, 10);

        if (minVal >= maxVal - PRICE_MIN_GAP) {
            minInput.value = maxVal - PRICE_MIN_GAP;
        }
        updatePriceSliderDisplay();
    };

    window.onMaxPriceInput = function(val) {
        const minInput = document.getElementById('ref-price-min');
        const maxInput = document.getElementById('ref-price-max');
        if (!minInput || !maxInput) return;

        maxInput.style.zIndex = '30';
        minInput.style.zIndex = '20';

        let minVal = parseInt(minInput.value, 10);
        let maxVal = parseInt(val, 10);

        if (maxVal <= minVal + PRICE_MIN_GAP) {
            maxInput.value = minVal + PRICE_MIN_GAP;
        }
        updatePriceSliderDisplay();
    };

    window.toggleFilterPanel = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const panel = document.getElementById('filter-panel');
        const chevron = document.getElementById('filter-chevron');
        const btn = document.getElementById('btn-toggle-filter');
        if (!panel) return;

        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            if (btn) btn.classList.add('bg-white/25', 'border-accent/60');
        } else {
            panel.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            if (btn) btn.classList.remove('bg-white/25', 'border-accent/60');
        }
    };

    window.toggleCustomDropdown = function(btn, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const parent = btn.closest('.custom-dropdown');
        if (!parent) return;
        const menu = parent.querySelector('.dropdown-menu');
        const chevron = parent.querySelector('.dropdown-chevron');

        // Close other open custom dropdowns
        document.querySelectorAll('.custom-dropdown').forEach(other => {
            if (other !== parent) {
                const otherMenu = other.querySelector('.dropdown-menu');
                const otherChevron = other.querySelector('.dropdown-chevron');
                if (otherMenu) otherMenu.classList.add('hidden');
                if (otherChevron) otherChevron.classList.remove('rotate-180');
            }
        });

        if (menu) {
            const isMenuHidden = menu.classList.contains('hidden');
            if (isMenuHidden) {
                menu.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }
    };

    window.selectDropdownOption = function(name, val, label, itemEl) {
        const parent = itemEl.closest('.custom-dropdown');
        if (!parent) return;
        const input = document.getElementById('input-' + name);
        const labelEl = parent.querySelector('.dropdown-label');
        const menu = parent.querySelector('.dropdown-menu');
        const chevron = parent.querySelector('.dropdown-chevron');

        if (input) input.value = val;
        if (labelEl) labelEl.textContent = label;

        parent.querySelectorAll('.checkmark').forEach(c => c.classList.add('hidden'));
        const check = itemEl.querySelector('.checkmark');
        if (check) check.classList.remove('hidden');

        if (menu) menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    };

    // Close any open custom dropdown or tamu popover when clicking anywhere outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown .dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
            document.querySelectorAll('.custom-dropdown .dropdown-chevron').forEach(chevron => {
                chevron.classList.remove('rotate-180');
            });
        }

        const tamuContainer = document.getElementById('tamu-container');
        const tamuPopover = document.getElementById('tamu-popover');
        if (tamuContainer && tamuPopover && !tamuContainer.contains(e.target)) {
            tamuPopover.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const checkIn = document.getElementById('ref-check-in');
        const checkOut = document.getElementById('ref-check-out');

        if (checkIn && checkOut) {
            checkIn.addEventListener('change', function() {
                if (checkOut.value && checkOut.value <= checkIn.value) {
                    const nextDate = new Date(checkIn.value + 'T00:00:00');
                    nextDate.setDate(nextDate.getDate() + 1);
                    checkOut.value = nextDate.toISOString().split('T')[0];
                }
                checkOut.min = checkIn.value;
                updateDateDisplay();
            });

            checkOut.addEventListener('change', function() {
                updateDateDisplay();
            });

            updateDateDisplay();
        }

        updatePriceSliderDisplay();
    });
</script>
@endpush
@endsection
