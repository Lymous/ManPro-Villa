<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Villa Bukit Bunga - Sewa Villa Keluarga & Gathering Pacet Mojokerto')</title>
    <meta name="description"
        content="@yield('meta_description', 'Pilihan terbaik sewa villa keluarga, rombongan sekolah, kampus, dan gathering di Pacet, Mojokerto. Fasilitas kolam renang, aula rapat, kapasitas s/d 125 orang.')">

    <!-- Google Fonts: DM Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/logo.svg') }}">

    <!-- FontAwesome & Tabler Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#0F2625',
                            dark: '#081716',
                            light: '#1B3B39',
                        },
                        accent: {
                            DEFAULT: '#C8A44E',
                            dark: '#A68233',
                            light: '#E2C882',
                        },
                        cream: {
                            DEFAULT: '#F9F8F5',
                            dark: '#EFECE6',
                        },
                    },
                }
            }
        }
    </script>

    <style>
        .font-display {
            font-family: 'Playfair Display', serif;
        }

        .font-sans {
            font-family: 'DM Sans', sans-serif;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .luxury-gradient {
            background: linear-gradient(135deg, rgba(15, 38, 37, 0.95) 0%, rgba(8, 23, 22, 0.98) 100%);
        }

        .hero-pattern {
            background-image: radial-gradient(rgba(200, 164, 78, 0.12) 1px, transparent 0);
            background-size: 28px 28px;
        }

        .link-underline {
            position: relative;
        }

        .link-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #C8A44E;
            transition: width 0.3s ease;
        }

        .link-underline:hover::after {
            width: 100%;
        }

        /* Navbar transparent state (home page) */
        #main-header.navbar-transparent {
            background: transparent !important;
            border-bottom-color: transparent !important;
            box-shadow: none !important;
        }

        #main-header.navbar-transparent .nav-text {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        #main-header.navbar-transparent .nav-text:hover {
            color: #ffffff !important;
        }

        #main-header.navbar-transparent .nav-logo-text {
            color: #ffffff !important;
        }

        #main-header.navbar-transparent .nav-search-btn {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.35) !important;
            color: #ffffff !important;
        }

        #main-header.navbar-transparent .nav-search-btn:hover {
            background: rgba(255, 255, 255, 0.25) !important;
        }

        #main-header.navbar-transparent .nav-mobile-btn {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.35) !important;
            color: #ffffff !important;
        }
    </style>
    @stack('styles')
</head>

<body
    class="font-sans antialiased bg-[#FAF9F6] text-gray-800 flex flex-col min-h-screen selection:bg-accent selection:text-white">

    <!-- Main Navigation Bar (Gaya Nginapsini: Logo Kiri, Menu Tengah, Cari & Masuk Kanan) -->
    <header id="main-header"
        class="{{ request()->routeIs('home') || request()->routeIs('packages.index') || request()->routeIs('about') ? 'fixed top-0 left-0 right-0 w-full navbar-transparent' : 'sticky top-0 bg-white/95 backdrop-blur-md border-b border-gray-200/70 shadow-sm' }} z-50 transition-all duration-500 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- 1. Brand Logo (Kiri) -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                    <img src="{{ asset('logo/logo.svg') }}" alt="Villa Bukit Bunga"
                        class="w-10 h-10 rounded-2xl object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <div>
                        <span
                            class="nav-logo-text font-display font-bold text-lg sm:text-xl text-primary tracking-tight block transition-colors duration-500">Villa
                            Bukit Bunga</span>
                        <span
                            class="text-[9px] sm:text-[10px] tracking-[0.25em] text-accent font-semibold uppercase block -mt-0.5">Pacet
                            • Mojokerto</span>
                    </div>
                </a>

                <!-- 2. Centered Menu Links (Tengah - Sesuai Kebutuhan Villa) -->
                <nav class="hidden md:flex items-center gap-8 lg:gap-10">
                    <a href="{{ route('packages.index') }}"
                        class="nav-text text-sm font-medium transition-colors {{ request()->routeIs('packages.*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-gray-700 hover:text-primary' }}">
                        Paket Villa
                    </a>
                    <a href="{{ route('home') }}#fasilitas"
                        class="nav-text text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        Fasilitas
                    </a>
                    <a href="{{ route('home') }}#cara-booking"
                        class="nav-text text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        Cara Pesan
                    </a>
                    <a href="{{ route('booking.track') }}"
                        class="nav-text text-sm font-medium transition-colors {{ request()->routeIs('booking.track') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-gray-700 hover:text-primary' }}">
                        Cek Booking
                    </a>
                    <a href="{{ route('about') }}"
                        class="nav-text text-sm font-medium text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('about') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : '' }}">
                        Tentang Kami
                    </a>
                </nav>

                <!-- 3. Actions: Kotak Pencarian & Tombol Masuk Kapsul (Kanan) -->
                <div class="flex items-center gap-3">
                    <!-- Tombol Search Kotak (Persis Referensi) -->
                    <button type="button" id="search-modal-btn"
                        class="nav-search-btn w-10 h-10 rounded-xl border border-gray-300/90 text-gray-700 hover:text-primary hover:border-primary flex items-center justify-center transition-all bg-white hover:bg-gray-50 focus:outline-none"
                        title="Cari Paket / Cek Booking" aria-label="Cari">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>

                    @guest
                        <!-- Tombol Masuk Kapsul (Persis Referensi) -->
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-primary hover:bg-primary-light text-white text-sm font-bold tracking-wide shadow-sm hover:shadow transition-all">
                            Masuk
                        </a>
                    @else
                        <!-- Dropdown Akun Pengguna (Untuk Kebutuhan Customer & Admin) -->
                        <div class="relative" id="user-menu-container">
                            <button type="button" id="user-menu-button"
                                class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-primary text-white hover:bg-primary-light text-sm font-semibold shadow-sm transition-all focus:outline-none">
                                <span
                                    class="w-6 h-6 rounded-full bg-accent text-primary font-display font-bold text-[11px] flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="max-w-[110px] truncate text-xs font-bold">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-accent transition-transform"
                                    id="user-menu-chevron"></i>
                            </button>

                            <!-- Dropdown Box -->
                            <div id="user-dropdown-box"
                                class="hidden absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/60 rounded-t-2xl">
                                    <p class="text-xs font-bold text-primary truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    <span
                                        class="inline-block mt-1 bg-accent/20 text-accent-dark text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        {{ auth()->user()->isCustomer() ? 'Akun Tamu' : 'Staff Admin' }}
                                    </span>
                                </div>

                                <div class="py-1">
                                    @if(auth()->user()->isCustomer())
                                        <a href="{{ route('customer.bookings') }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-cream hover:text-primary transition-colors">
                                            <i class="fa-solid fa-calendar-check text-accent text-sm w-4"></i>
                                            <span>Pesanan Saya</span>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-cream hover:text-primary transition-colors">
                                            <i class="fa-solid fa-chart-pie text-accent text-sm w-4"></i>
                                            <span>Panel Admin</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('booking.track') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-cream hover:text-primary transition-colors">
                                        <i class="fa-solid fa-receipt text-gray-400 text-sm w-4"></i>
                                        <span>Lacak Nomor Booking</span>
                                    </a>
                                </div>

                                <div class="border-t border-gray-100 pt-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                            <i class="fa-solid fa-right-from-bracket text-sm w-4"></i>
                                            <span>Keluar (Logout)</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <!-- Mobile Hamburger Toggle -->
                    <button type="button" id="mobile-toggle-btn"
                        class="nav-mobile-btn md:hidden p-2.5 rounded-xl border border-gray-300/80 text-gray-700 hover:bg-gray-100 hover:text-primary transition-colors focus:outline-none"
                        aria-label="Menu Navigasi">
                        <i id="mobile-toggle-icon" class="fa-solid fa-bars text-base"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer / Dropdown Menu -->
        <div id="mobile-menu-drawer"
            class="hidden md:hidden border-t border-gray-100 bg-white/98 backdrop-blur-lg px-4 pt-3 pb-6 space-y-4 shadow-xl">
            <!-- Nav Links Mobile -->
            <nav class="flex flex-col space-y-1">
                <a href="{{ route('packages.index') }}"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('packages.*') ? 'bg-accent/10 text-primary font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>Paket Villa</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a href="{{ route('home') }}#fasilitas"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <span>Fasilitas</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a href="{{ route('home') }}#cara-booking"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <span>Cara Pesan</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a href="{{ route('booking.track') }}"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('booking.track') ? 'bg-accent/10 text-primary font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>Cek Booking</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a href="{{ route('about') }}"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <span>Tentang Kami</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
            </nav>

            <!-- User / Auth Section Mobile -->
            <div class="pt-3 border-t border-gray-100 flex flex-col gap-2.5">
                @auth
                    <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-full bg-primary text-accent font-display font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left">
                                <span
                                    class="block text-xs font-bold text-primary truncate max-w-[150px]">{{ auth()->user()->name }}</span>
                                <span
                                    class="block text-[10px] text-accent-dark font-medium">{{ auth()->user()->email }}</span>
                            </div>
                        </div>

                        @if(auth()->user()->isCustomer())
                            <a href="{{ route('customer.bookings') }}"
                                class="text-[11px] font-bold px-3 py-1.5 rounded-lg bg-accent text-primary">
                                Pesanan
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-[11px] font-bold px-3 py-1.5 rounded-lg bg-primary text-white">
                                Admin
                            </a>
                        @endif
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full text-center text-xs font-bold py-3 rounded-full bg-primary text-white hover:bg-primary-light transition-colors shadow-sm">
                        Masuk / Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Modal Dialog Pencarian Cepat & Cek Booking -->
    <div id="search-modal"
        class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm hidden flex items-start justify-center pt-20 sm:pt-28 px-4 transition-all">
        <div
            class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-gray-100 relative animate-in fade-in zoom-in-95 duration-200">
            <!-- Close Button -->
            <button type="button" id="search-modal-close"
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-primary flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-accent/20 text-accent-dark flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass text-base"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-lg text-primary">Cari & Lacak</h3>
                    <p class="text-xs text-gray-500">Temukan paket villa atau lacak status booking Anda</p>
                </div>
            </div>

            <!-- Form Cek Booking Instan -->
            <form action="{{ route('booking.track') }}" method="GET" class="mb-5">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Lacak Kode
                    Booking</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <i
                            class="fa-solid fa-receipt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="kode" placeholder="Contoh: VBB-20260906-XXXX"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                    </div>
                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-accent text-primary text-xs font-bold hover:bg-accent-dark hover:text-white transition-colors">
                        Lacak
                    </button>
                </div>
            </form>

            <div class="border-t border-gray-100 pt-4">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Pilihan Cepat Paket
                    Villa</span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('packages.show', 'paket-1-family-retreat') }}"
                        class="p-2.5 rounded-xl bg-gray-50 hover:bg-cream border border-gray-100 text-gray-700 hover:text-primary transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-bed text-accent text-xs"></i>
                        <span class="truncate">Paket 1 (Family)</span>
                    </a>
                    <a href="{{ route('packages.show', 'paket-3-gathering-family-plus') }}"
                        class="p-2.5 rounded-xl bg-gray-50 hover:bg-cream border border-gray-100 text-gray-700 hover:text-primary transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-users text-accent text-xs"></i>
                        <span class="truncate">Paket 3 (Gathering)</span>
                    </a>
                    <a href="{{ route('packages.show', 'paket-6-big-group-harmony') }}"
                        class="p-2.5 rounded-xl bg-gray-50 hover:bg-cream border border-gray-100 text-gray-700 hover:text-primary transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-hotel text-accent text-xs"></i>
                        <span class="truncate">Paket 6 (Rombongan)</span>
                    </a>
                    <a href="{{ route('packages.show', 'paket-9-full-villa-royalty') }}"
                        class="p-2.5 rounded-xl bg-gray-50 hover:bg-cream border border-gray-100 text-gray-700 hover:text-primary transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-crown text-accent text-xs"></i>
                        <span class="truncate">Paket 9 (Full Villa)</span>
                    </a>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('packages.index') }}"
                        class="text-xs font-semibold text-primary hover:text-accent transition-colors link-underline">
                        Lihat Seluruh 9 Paket Villa &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Alert Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-xl"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    <!-- Main Content Injection -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6285808408835?text=Halo%20Villa%20Bukit%20Bunga%2C%20saya%20ingin%20tanya%20sewa%20villa"
        target="_blank"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-3.5 rounded-full shadow-2xl hover:scale-105 transition-all duration-300 group">
        <i class="fa-brands fa-whatsapp text-2xl group-hover:rotate-12 transition-transform"></i>
        <span class="text-xs font-bold tracking-wide hidden sm:inline">Hubungi Villa</span>
    </a>

    <!-- Footer Megah Bergaya Nginapsini.id -->
    <footer class="bg-primary text-white relative overflow-hidden mt-20 border-t border-white/5">
        <div class="hero-pattern absolute inset-0 pointer-events-none opacity-40"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12">
                <!-- Col 1: Brand Info -->
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ asset('logo/logo.svg') }}" alt="Villa Bukit Bunga"
                            class="w-11 h-11 rounded-2xl object-cover shadow-sm">
                        <div>
                            <h3 class="font-display font-bold text-xl text-white">Villa Bukit Bunga</h3>
                            <p class="text-xs text-accent uppercase tracking-widest">Pacet • Mojokerto</p>
                        </div>
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed mb-6 max-w-sm">
                        Akomodasi villa keluarga dan rombongan gathering terbaik di kawasan sejuk Pacet, Mojokerto.
                        Dilengkapi aula pertemuan luas, kolam renang bersih, sound system, serta akses dekat pemandian
                        air panas.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="https://wa.me/6285808408835" target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-accent hover:text-primary transition-colors flex items-center justify-center text-sm">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-accent hover:text-primary transition-colors flex items-center justify-center text-sm">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://maps.google.com" target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-accent hover:text-primary transition-colors flex items-center justify-center text-sm">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold text-accent uppercase tracking-wider mb-5">Navigasi</h4>
                    <ul class="space-y-3 text-sm text-white/70">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('packages.index') }}" class="hover:text-white transition-colors">Pilihan
                                Paket (1-9)</a></li>
                        <li><a href="{{ route('home') }}#fasilitas" class="hover:text-white transition-colors">Fasilitas
                                Villa</a></li>
                        <li><a href="{{ route('booking.track') }}" class="hover:text-white transition-colors">Cek Status
                                Pesanan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Staf &
                                Admin</a></li>
                    </ul>
                </div>

                <!-- Col 3: Paket Favorit -->
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold text-accent uppercase tracking-wider mb-5">Paket Populer</h4>
                    <ul class="space-y-3 text-sm text-white/70">
                        <li><a href="{{ route('packages.show', 'paket-1-family-retreat') }}"
                                class="hover:text-white transition-colors">Paket 1 (Keluarga)</a></li>
                        <li><a href="{{ route('packages.show', 'paket-3-gathering-family-plus') }}"
                                class="hover:text-white transition-colors">Paket 3 (Gathering)</a></li>
                        <li><a href="{{ route('packages.show', 'paket-6-big-group-harmony') }}"
                                class="hover:text-white transition-colors">Paket 6 (Rombongan)</a></li>
                        <li><a href="{{ route('packages.show', 'paket-9-full-villa-royalty') }}"
                                class="hover:text-white transition-colors">Paket 9 (Full Villa)</a></li>
                    </ul>
                </div>

                <!-- Col 4: Lokasi & Kontak -->
                <div class="md:col-span-3">
                    <h4 class="text-xs font-semibold text-accent uppercase tracking-wider mb-5">Kontak & Lokasi</h4>
                    <ul class="space-y-3.5 text-sm text-white/70">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-accent mt-1 text-xs"></i>
                            <span>Jln. Air Panas KM. 2, Gapuk, Made, Pacet, Mojokerto, Jawa Timur</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                            <a href="https://wa.me/6285808408835" class="hover:text-white">0858 0840 8835</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div
                class="border-t border-white/10 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/50">
                <p>&copy; {{ date('Y') }} Villa Bukit Bunga Pacet. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <span class="text-white/70">QRIS • Virtual Account Bank • Transfer</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Navbar Interactivity Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // User Menu Dropdown Toggle
            const userMenuBtn = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown-box');
            const userChevron = document.getElementById('user-menu-chevron');

            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isExpanded = userDropdown.classList.contains('hidden');
                    userDropdown.classList.toggle('hidden');
                    if (userChevron) {
                        userChevron.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        if (userChevron) {
                            userChevron.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            }

            // Mobile Menu Drawer Toggle
            const mobileToggleBtn = document.getElementById('mobile-toggle-btn');
            const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
            const mobileToggleIcon = document.getElementById('mobile-toggle-icon');

            if (mobileToggleBtn && mobileMenuDrawer) {
                mobileToggleBtn.addEventListener('click', function () {
                    mobileMenuDrawer.classList.toggle('hidden');
                    const isOpen = !mobileMenuDrawer.classList.contains('hidden');

                    if (isOpen) {
                        mobileToggleIcon.classList.remove('fa-bars');
                        mobileToggleIcon.classList.add('fa-xmark');
                    } else {
                        mobileToggleIcon.classList.remove('fa-xmark');
                        mobileToggleIcon.classList.add('fa-bars');
                    }
                });
            }

            // Search Modal Trigger & Close Handlers
            const searchModalBtn = document.getElementById('search-modal-btn');
            const searchModal = document.getElementById('search-modal');
            const searchModalClose = document.getElementById('search-modal-close');

            if (searchModalBtn && searchModal) {
                searchModalBtn.addEventListener('click', function () {
                    searchModal.classList.remove('hidden');
                    const input = searchModal.querySelector('input');
                    if (input) setTimeout(() => input.focus(), 100);
                });

                if (searchModalClose) {
                    searchModalClose.addEventListener('click', function () {
                        searchModal.classList.add('hidden');
                    });
                }

                // Close on backdrop click
                searchModal.addEventListener('click', function (e) {
                    if (e.target === searchModal) {
                        searchModal.classList.add('hidden');
                    }
                });

                // Close on ESC key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                        searchModal.classList.add('hidden');
                    }
                });
            }

            // Navbar: Transparan di awal pada halaman dengan hero gelap, solid saat digulir ke bawah
            const mainHeader = document.getElementById('main-header');
            const isDarkHeroPage = @json(request()->routeIs('home') || request()->routeIs('packages.index') || request()->routeIs('about'));

            if (mainHeader && isDarkHeroPage) {
                const SCROLL_THRESHOLD = 80;

                const applyScrolledState = () => {
                    // State: solid/jelas dengan background putih
                    mainHeader.classList.remove('navbar-transparent');
                    mainHeader.classList.add(
                        'bg-white/95', 'backdrop-blur-md',
                        'border-b', 'border-gray-200/70', 'shadow-md'
                    );
                };

                const applyTransparentState = () => {
                    // State: transparan di puncak halaman
                    mainHeader.classList.add('navbar-transparent');
                    mainHeader.classList.remove(
                        'bg-white/95', 'backdrop-blur-md',
                        'border-b', 'border-gray-200/70', 'shadow-md'
                    );

                    // Tutup dropdown jika ada saat kembali ke puncak
                    const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
                    const userDropdown = document.getElementById('user-dropdown-box');
                    if (mobileMenuDrawer) mobileMenuDrawer.classList.add('hidden');
                    if (userDropdown) userDropdown.classList.add('hidden');
                };

                const handleHeaderScroll = () => {
                    if (window.scrollY > SCROLL_THRESHOLD) {
                        applyScrolledState();
                    } else {
                        applyTransparentState();
                    }
                };

                window.addEventListener('scroll', handleHeaderScroll, { passive: true });
                // Jalankan sekali saat load untuk state awal
                handleHeaderScroll();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>