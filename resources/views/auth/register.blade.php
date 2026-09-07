@extends('layouts.app')

@section('title', 'Daftar Akun Tamu - Villa Bukit Bunga Pacet')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12 sm:py-16">
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-200/80 shadow-2xl p-5 sm:p-10 relative overflow-hidden">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-4 group">
                <img src="{{ asset('logo/logo.svg') }}" alt="Villa Bukit Bunga" class="w-16 h-16 rounded-2xl object-cover mx-auto shadow-md group-hover:scale-105 transition-transform duration-300">
            </a>
            <h1 class="font-display text-xl sm:text-2xl font-bold text-primary">Daftar Akun Tamu</h1>
            <p class="text-xs text-gray-500 mt-1">Buat akun untuk melacak riwayat reservasi villa dengan mudah</p>
        </div>

        @if(session('url.intended') && str_contains(session('url.intended'), 'checkout'))
        <div class="bg-amber-50 border border-amber-200 text-amber-900 text-xs p-4 rounded-2xl mb-6 flex items-start gap-2.5">
            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0 text-sm"></i>
            <div>
                <span class="font-bold block text-amber-950">Pemesanan Memerlukan Akun</span>
                Silakan daftar akun tamu terlebih dahulu untuk melanjutkan pemesanan villa. Setelah pendaftaran selesai, Anda akan langsung diarahkan ke halaman pemesanan.
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-2xl mb-6">
            <i class="fa-solid fa-circle-exclamation mr-1.5 text-rose-500"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap Anda..." 
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 081234567890" 
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                <span class="text-[10px] text-gray-400 mt-1 block">Digunakan untuk konfirmasi pesanan & invoice.</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com" 
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Kata Sandi *</label>
                    <input type="password" name="password" required placeholder="Min 6 karakter" 
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ulangi Sandi *</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi sandi" 
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-primary hover:bg-primary-light text-white font-bold text-sm shadow-md transition-all">
                    Daftar Akun Sekarang
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
            <span>Sudah memiliki akun?</span>
            <a href="{{ route('login') }}" class="font-bold text-accent hover:underline ml-1">Masuk ke Akun</a>
        </div>
    </div>
</div>
@endsection
