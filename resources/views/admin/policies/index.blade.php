@extends('layouts.admin')

@section('title', 'Aturan & Kebijakan Bisnis - Villa Bukit Bunga')
@section('page_title', 'Pengaturan Kebijakan & Aturan Sistem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="font-display text-xl font-bold text-primary">Kebijakan Operasional & Pembayaran</h3>
            <p class="text-xs text-gray-500">Nilai aturan ini tersimpan dinamis di database tabel <code>policies</code> dan dapat disesuaikan sewaktu-waktu tanpa mengubah kode program.</p>
        </div>

        <form action="{{ route('admin.policies.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($policies as $p)
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ $p->label }}
                    </label>
                    <input type="text" 
                           name="policies[{{ $p->key }}]" 
                           value="{{ old('policies.' . $p->key, $p->value) }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                    <span class="text-[10px] text-gray-400 block">Kunci: <code>{{ $p->key }}</code></span>
                </div>
                @endforeach
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-primary hover:bg-primary-light text-white text-xs font-bold shadow-md transition-all">
                    Simpan Perubahan Kebijakan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
