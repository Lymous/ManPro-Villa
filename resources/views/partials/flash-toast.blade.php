@php
    $hasSuccess = session()->has('success');
    $hasError = session()->has('error');
    $hasInfo = session()->has('info');
    $authToast = session('auth_toast');
@endphp

@if($hasSuccess || $hasError || $hasInfo)
    @php
        $type = $hasError ? 'error' : ($hasInfo ? 'info' : 'success');
        $message = session('success') ?? session('error') ?? session('info');
        
        $isLogin = $authToast === 'login' || str_contains($message, 'Selamat datang');
        $isLogout = $authToast === 'logout' || str_contains(strtolower($message), 'berhasil keluar');
        
        if ($isLogin) {
            $title = 'Berhasil Masuk';
            $icon = 'fa-solid fa-arrow-right-to-bracket';
            $badgeText = 'Sesi Aktif';
            $accentColor = 'emerald';
        } elseif ($isLogout) {
            $title = 'Berhasil Keluar';
            $icon = 'fa-solid fa-arrow-right-from-bracket';
            $badgeText = 'Sampai Jumpa';
            $accentColor = 'emerald';
        } elseif ($type === 'error') {
            $title = 'Terjadi Kesalahan';
            $icon = 'fa-solid fa-circle-exclamation';
            $badgeText = 'Gagal';
            $accentColor = 'rose';
        } else {
            $title = 'Berhasil';
            $icon = 'fa-solid fa-circle-check';
            $badgeText = 'Sukses';
            $accentColor = 'emerald';
        }
    @endphp

    <!-- Toast Notification Floating Container -->
    <div id="flash-toast-container"
         class="fixed top-5 right-5 sm:top-6 sm:right-6 z-[9999] max-w-sm sm:max-w-md w-[calc(100%-2.5rem)] pointer-events-auto transition-all duration-300 ease-out transform -translate-y-3 opacity-0"
         style="filter: drop-shadow(0 15px 35px rgba(15, 38, 37, 0.18));">
        <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-gray-100/90 shadow-xl overflow-hidden relative group">
            <div class="p-4 sm:p-4.5 flex items-start gap-3.5">
                <!-- Icon Badge -->
                @if($accentColor === 'rose')
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 shadow-inner">
                        <i class="{{ $icon }} text-base"></i>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100/80 shadow-inner">
                        <i class="{{ $icon }} text-base"></i>
                    </div>
                @endif

                <!-- Content Info -->
                <div class="flex-1 min-w-0 pr-2">
                    <div class="flex items-center gap-2 mb-0.5">
                        <h4 class="font-bold text-gray-900 text-sm tracking-tight font-sans">{{ $title }}</h4>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider {{ $accentColor === 'rose' ? 'bg-rose-100/80 text-rose-700' : 'bg-emerald-100/80 text-emerald-800' }}">
                            {{ $badgeText }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 font-medium leading-relaxed break-words">
                        {{ $message }}
                    </p>
                </div>

                <!-- Dismiss Button -->
                <button type="button" 
                        onclick="dismissFlashToast()" 
                        class="w-7 h-7 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center transition-colors shrink-0 -mr-1 -mt-1 cursor-pointer"
                        title="Tutup Notifikasi"
                        aria-label="Tutup Notifikasi">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <!-- Auto-dismiss Countdown Progress Bar (Maksimal 2 Detik / 2000ms) -->
            <div class="h-1 w-full bg-gray-100 overflow-hidden">
                <div id="flash-toast-progress" 
                     class="h-full {{ $accentColor === 'rose' ? 'bg-rose-500' : 'bg-emerald-500' }} w-full"
                     style="transition: width 2000ms linear; width: 100%;"></div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const toast = document.getElementById('flash-toast-container');
            const progress = document.getElementById('flash-toast-progress');
            if (!toast) return;

            // Delay maksimal 2 detik sesuai permintaan user
            const DURATION = 2000;

            // Animasi Masuk
            requestAnimationFrame(() => {
                setTimeout(() => {
                    toast.classList.remove('-translate-y-3', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                    if (progress) {
                        progress.style.width = '0%';
                    }
                }, 40);
            });

            // Fungsi Tutup & Hapus Elemen
            window.dismissFlashToast = function() {
                if (!toast) return;
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('opacity-0', 'translate-x-8', 'scale-95');
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            };

            // Timer Otomatis Maksimal 2 Detik
            const autoDismissTimer = setTimeout(() => {
                window.dismissFlashToast();
            }, DURATION);

            // Bersihkan timer jika ditutup sebelum durasi habis
            toast.addEventListener('click', (e) => {
                if (e.target.closest('button')) {
                    clearTimeout(autoDismissTimer);
                }
            });
        })();
    </script>
@endif
