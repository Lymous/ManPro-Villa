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

    <!-- Sidebar -->
    <aside class="w-64 bg-primary text-white flex flex-col shrink-0 border-r border-white/10 select-none">
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
        <header class="h-20 bg-white border-b border-gray-200/80 px-8 flex items-center justify-between shrink-0 shadow-sm">
            <h2 class="font-display text-xl font-bold text-primary">@yield('page_title', 'Dasbor')</h2>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-full border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square text-accent"></i>
                    <span>Buka Web Publik</span>
                </a>
            </div>
        </header>

        <!-- Alert Notifications -->
        @if(session('success'))
        <div class="px-8 pt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs px-4 py-3 rounded-2xl flex items-center justify-between">
                <span><i class="fa-solid fa-check-circle mr-1.5 text-emerald-600"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="px-8 pt-4">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs px-4 py-3 rounded-2xl flex items-center justify-between">
                <span><i class="fa-solid fa-exclamation-circle mr-1.5 text-rose-600"></i> {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        @endif

        <!-- Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
