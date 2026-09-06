@extends('layouts.app')

@section('title', 'Masuk ke Akun - Villa Bukit Bunga Pacet')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-200/80 shadow-2xl p-8 sm:p-10 relative overflow-hidden">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-4 group">
                <img src="{{ asset('logo/logo.svg') }}" alt="Villa Bukit Bunga" class="w-16 h-16 rounded-2xl object-cover mx-auto shadow-md group-hover:scale-105 transition-transform duration-300">
            </a>
            <h1 class="font-display text-2xl font-bold text-primary">Masuk ke Akun Anda</h1>
            <p class="text-xs text-gray-500 mt-1">Masuk untuk melanjutkan reservasi villa atau mengelola pesanan Anda</p>
        </div>

        @if(session('url.intended') && str_contains(session('url.intended'), 'checkout'))
        <div class="bg-amber-50 border border-amber-200 text-amber-900 text-xs p-4 rounded-2xl mb-6 flex items-start gap-2.5">
            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0 text-sm"></i>
            <div>
                <span class="font-bold block text-amber-950">Pemesanan Memerlukan Login</span>
                Silakan masuk ke akun Anda terlebih dahulu untuk melanjutkan pemesanan. Setelah masuk, Anda akan langsung diarahkan kembali ke formulir pemesanan.
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-2xl mb-6">
            <i class="fa-solid fa-circle-exclamation mr-1.5 text-rose-500"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Akun</label>
                <input type="email" name="email" id="login-email" value="{{ old('email') }}" required placeholder="nama@email.com" 
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <input type="password" name="password" id="login-password" required placeholder="Masukkan kata sandi..." 
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                    <input type="checkbox" name="remember" class="accent-primary rounded">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-md transition-all">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">
            <span>Belum memiliki akun tamu?</span>
            <a href="{{ route('register') }}" class="font-bold text-accent hover:underline ml-1">Daftar Akun Tamu</a>
        </div>

        <!-- Quick Demo Switcher -->
        <div class="mt-6 pt-6 border-t border-gray-100 text-center">
            <span class="text-[11px] text-gray-400 block mb-2 font-medium">Quick Demo Akun Penguji:</span>
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button type="button" onclick="fillCustomer()" class="px-2.5 py-1.5 bg-accent/15 text-accent-dark hover:bg-accent hover:text-white rounded-lg text-xs font-bold transition-colors">
                    <i class="fa-solid fa-user mr-1"></i> Tamu (Dewi)
                </button>
                <button type="button" onclick="fillAdmin()" class="px-2.5 py-1.5 bg-gray-100 hover:bg-primary hover:text-white rounded-lg text-xs font-bold transition-colors">
                    Admin
                </button>
                <button type="button" onclick="fillPenjaga()" class="px-2.5 py-1.5 bg-gray-100 hover:bg-primary hover:text-white rounded-lg text-xs font-bold transition-colors">
                    Penjaga Villa
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fillCustomer() {
        document.getElementById('login-email').value = 'dewi@example.com';
        document.getElementById('login-password').value = 'password123';
    }
    function fillAdmin() {
        document.getElementById('login-email').value = 'admin@bukitbunga.com';
        document.getElementById('login-password').value = 'password123';
    }
    function fillPenjaga() {
        document.getElementById('login-email').value = 'penjaga@bukitbunga.com';
        document.getElementById('login-password').value = 'password123';
    }
</script>
@endpush
@endsection
