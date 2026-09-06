@extends('layouts.admin')

@section('title', 'Blokir Tanggal Pemeliharaan - Villa Bukit Bunga')
@section('page_title', 'Jadwal Blokir Pemeliharaan & Pribadi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Form Tambah Blokir -->
    <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-5">
        <div class="border-b border-gray-100 pb-3">
            <h3 class="font-display text-lg font-bold text-primary">Kunci Tanggal (Blokir)</h3>
            <p class="text-xs text-gray-500">Mencegah tamu memesan pada tanggal perawatan kolam atau acara keluarga pemilik.</p>
        </div>

        <form action="{{ route('admin.blocked_dates.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Target Blokir</label>
                <select name="room_type_id" class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                    <option value="">-- Kunci Seluruh Area Villa --</option>
                    @foreach($roomTypes as $rt)
                    <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Mulai *</label>
                    <input type="date" name="tanggal_mulai" required min="{{ now()->format('Y-m-d') }}" class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Selesai *</label>
                    <input type="date" name="tanggal_selesai" required min="{{ now()->format('Y-m-d') }}" class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alasan Blokir *</label>
                <select name="alasan" required class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                    <option value="pemeliharaan" selected>Pemeliharaan (Maintenance Kolam / Bangunan)</option>
                    <option value="pribadi">Pemakaian Pribadi / Keluarga Owner</option>
                    <option value="libur">Libur Operasional</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Catatan Tambahan</label>
                <input type="text" name="catatan" placeholder="Kuras kolam renang pacet, pengecatan aula..." class="w-full p-2.5 rounded-xl border border-gray-200 text-xs outline-none">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-primary hover:bg-primary-light text-white text-xs font-bold transition-all shadow-sm">
                Tambahkan Blokir Tanggal
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Blokir -->
    <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-4">
        <h3 class="font-display text-lg font-bold text-primary border-b border-gray-100 pb-3">Daftar Tanggal yang Terkunci</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase font-bold">
                    <tr>
                        <th class="p-3">Target</th>
                        <th class="p-3">Periode</th>
                        <th class="p-3">Alasan</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($blockedDates as $bd)
                    <tr>
                        <td class="p-3 font-bold text-primary">
                            {{ $bd->roomType ? $bd->roomType->nama : 'Seluruh Villa' }}
                        </td>
                        <td class="p-3 font-mono">
                            {{ $bd->tanggal_mulai->format('d/m/Y') }} – {{ $bd->tanggal_selesai->format('d/m/Y') }}
                        </td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                {{ ucfirst($bd->alasan) }}
                            </span>
                            @if($bd->catatan)
                            <span class="block text-[10px] text-gray-400 mt-0.5">{{ $bd->catatan }}</span>
                            @endif
                        </td>
                        <td class="p-3 text-right">
                            <form action="{{ route('admin.blocked_dates.destroy', $bd->id) }}" method="POST" onsubmit="return confirm('Hapus kunci tanggal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 p-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400 italic">
                            Tidak ada tanggal yang sedang diblokir.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
