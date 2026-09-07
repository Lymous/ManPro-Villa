<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Villa Bukit Bunga')</title>

    <!-- Google Fonts: DM Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/logo.svg') }}">

    <!-- FontAwesome -->
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
                    },
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100/70 text-gray-800 flex h-screen overflow-hidden">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="mobile-admin-backdrop" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    <!-- Mobile Sidebar Drawer (Off-canvas) -->
    <aside id="mobile-admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-primary text-white flex flex-col shrink-0 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden shadow-2xl select-none">
        <!-- Brand Header with Close Button -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logo.svg') }}" alt="Villa Bukit Bunga" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                <div>
                    <span class="font-display font-bold text-sm text-white tracking-wide block">Villa Bukit Bunga</span>
                    <span class="text-[10px] text-accent font-semibold uppercase tracking-widest block">Admin Panel</span>
                </div>
            </div>
            <button type="button" id="mobile-admin-close-btn" class="p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Tutup Menu">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-xs font-semibold">
            <div class="text-[10px] uppercase tracking-wider text-white/40 px-3 pb-1">Operasional</div>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                <span>Dasbor & Kalender</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.bookings.index', 'admin.bookings.show') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-calendar-check w-4 text-center"></i>
                <span>Data Reservasi</span>
            </a>

            <a href="{{ route('admin.bookings.create') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.bookings.create') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-circle-plus w-4 text-center"></i>
                <span>Input Booking Manual</span>
            </a>

            @if(auth()->user()->isAdmin())
            <div class="text-[10px] uppercase tracking-wider text-white/40 px-3 pt-5 pb-1">Khusus Admin</div>

            <a href="{{ route('admin.payments.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.payments.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-credit-card w-4 text-center"></i>
                <span>Verifikasi Pembayaran</span>
            </a>

            <a href="{{ route('admin.rooms.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.rooms.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-door-open w-4 text-center"></i>
                <span>Paket & Kamar Fisik</span>
            </a>

            <a href="{{ route('admin.blocked_dates.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.blocked_dates.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-ban w-4 text-center"></i>
                <span>Blokir Pemeliharaan</span>
            </a>

            <a href="{{ route('admin.policies.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.policies.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-sliders w-4 text-center"></i>
                <span>Aturan & Kebijakan</span>
            </a>
            @endif
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-white/10 bg-black/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-accent/20 text-accent flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="font-bold text-xs text-white block truncate max-w-[130px]">{{ auth()->user()->name }}</span>
                        <span class="text-[9px] uppercase tracking-wider text-accent font-semibold block">
                            {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Penjaga Villa' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-rose-400 text-sm p-1.5 transition-colors" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Desktop Sidebar (Permanent on lg screens) -->
    <aside class="hidden lg:flex w-64 bg-primary text-white flex-col shrink-0 border-r border-white/10 select-none">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 gap-3 border-b border-white/10">
            <img src="{{ asset('logo/logo.svg') }}" alt="Villa Bukit Bunga" class="w-10 h-10 rounded-xl object-cover shadow-sm">
            <div>
                <span class="font-display font-bold text-sm text-white tracking-wide block">Villa Bukit Bunga</span>
                <span class="text-[10px] text-accent font-semibold uppercase tracking-widest block">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-xs font-semibold">
            <div class="text-[10px] uppercase tracking-wider text-white/40 px-3 pb-1">Operasional</div>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                <span>Dasbor & Kalender</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.bookings.index', 'admin.bookings.show') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-calendar-check w-4 text-center"></i>
                <span>Data Reservasi</span>
            </a>

            <a href="{{ route('admin.bookings.create') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.bookings.create') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-circle-plus w-4 text-center"></i>
                <span>Input Booking Manual</span>
            </a>

            @if(auth()->user()->isAdmin())
            <div class="text-[10px] uppercase tracking-wider text-white/40 px-3 pt-5 pb-1">Khusus Admin</div>

            <a href="{{ route('admin.payments.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.payments.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-credit-card w-4 text-center"></i>
                <span>Verifikasi Pembayaran</span>
            </a>

            <a href="{{ route('admin.rooms.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.rooms.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-door-open w-4 text-center"></i>
                <span>Paket & Kamar Fisik</span>
            </a>

            <a href="{{ route('admin.blocked_dates.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.blocked_dates.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-ban w-4 text-center"></i>
                <span>Blokir Pemeliharaan</span>
            </a>

            <a href="{{ route('admin.policies.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.policies.*') ? 'bg-accent text-primary font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-sliders w-4 text-center"></i>
                <span>Aturan & Kebijakan</span>
            </a>
            @endif
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-white/10 bg-black/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-accent/20 text-accent flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="font-bold text-xs text-white block truncate max-w-[110px]">{{ auth()->user()->name }}</span>
                        <span class="text-[9px] uppercase tracking-wider text-accent font-semibold block">
                            {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Penjaga Villa' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-rose-400 text-sm p-1.5 transition-colors" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar -->
        <header class="h-16 sm:h-20 bg-white border-b border-gray-200/80 px-4 sm:px-8 flex items-center justify-between shrink-0 shadow-xs">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Mobile Hamburger Button -->
                <button type="button" id="admin-mobile-toggle" 
                        class="lg:hidden p-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-100 hover:text-primary transition-colors focus:outline-none"
                        aria-label="Buka Menu Admin">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <h2 class="font-display text-base sm:text-xl font-bold text-primary truncate">@yield('page_title', 'Dasbor')</h2>
            </div>

            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                <a href="{{ route('home') }}" target="_blank" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square text-accent text-xs"></i>
                    <span class="hidden sm:inline">Buka Web Publik</span>
                    <span class="sm:hidden">Web</span>
                </a>
            </div>
        </header>

        <!-- Floating Modern Toast Notifications -->
        @include('partials.flash-toast')

        <!-- Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Admin Drawer Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('admin-mobile-toggle');
            const closeBtn = document.getElementById('mobile-admin-close-btn');
            const drawer = document.getElementById('mobile-admin-sidebar');
            const backdrop = document.getElementById('mobile-admin-backdrop');

            function openDrawer() {
                if (drawer && backdrop) {
                    backdrop.classList.remove('hidden');
                    setTimeout(() => {
                        backdrop.classList.remove('opacity-0');
                        drawer.classList.remove('-translate-x-full');
                    }, 10);
                }
            }

            function closeDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.add('-translate-x-full');
                    backdrop.classList.add('opacity-0');
                    setTimeout(() => {
                        backdrop.classList.add('hidden');
                    }, 300);
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);
        });
    </script>

    @stack('scripts')
</body>
</html>
