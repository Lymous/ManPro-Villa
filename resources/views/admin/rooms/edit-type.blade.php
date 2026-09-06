@extends('layouts.admin')

@section('title', 'Edit Paket & Foto - ' . $roomType->nama)
@section('page_title', 'Kelola Paket & Galeri Foto')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-accent">Villa Bukit Bunga Pacet</span>
                <span class="text-gray-300">•</span>
                <span class="text-xs text-gray-400">ID Paket: #{{ $roomType->id }}</span>
            </div>
            <h2 class="font-display text-2xl font-bold text-primary">{{ $roomType->nama }}</h2>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('packages.show', $roomType->slug) }}" target="_blank" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-eye text-accent mr-1"></i> Lihat di Web Publik
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-700 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- SEKSI 1: MANAJEMEN FOTO & GALERI (Fokus Kemudahan Admin) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h3 class="font-display text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-images text-accent"></i>
                Kelola Galeri Foto Paket
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                Upload foto baru dari komputer/HP, jadikan foto favorit sebagai gambar utama (thumbnail), atau hapus foto yang tidak diperlukan.
            </p>
        </div>

        <!-- Grid Galeri Foto yang Sudah Ada -->
        <div>
            <span class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">
                Foto yang Sedang Aktif di Galeri ({{ $roomType->photos->count() }} Foto):
            </span>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @forelse($roomType->photos as $photo)
                <div class="group relative rounded-2xl overflow-hidden border-2 {{ $photo->is_primary ? 'border-accent shadow-md' : 'border-gray-200' }} bg-gray-50 flex flex-col justify-between">
                    <!-- Photo Display -->
                    <div class="relative h-36 overflow-hidden bg-gray-100">
                        <img src="{{ $photo->file_path }}" alt="{{ $photo->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        
                        @if($photo->is_primary)
                        <span class="absolute top-2 left-2 bg-accent text-primary text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                            <i class="fa-solid fa-star mr-1"></i> Foto Utama
                        </span>
                        @endif
                    </div>

                    <!-- Action Bar -->
                    <div class="p-2.5 bg-white flex items-center justify-between border-t border-gray-100">
                        @if(!$photo->is_primary)
                        <form action="{{ route('admin.rooms.set_primary_photo', $photo->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[11px] font-bold text-accent hover:text-accent-dark flex items-center gap-1">
                                <i class="fa-regular fa-star"></i> Jadikan Utama
                            </button>
                        </form>
                        @else
                        <span class="text-[10px] text-gray-400 italic">Thumbnail Aktif</span>
                        @endif

                        <form action="{{ route('admin.rooms.delete_photo', $photo->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-rose-600 p-1 transition-colors" title="Hapus Foto">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-8 text-center text-gray-400 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <i class="fa-solid fa-image text-3xl mb-2 block text-gray-300"></i>
                    <p class="text-xs">Belum ada foto tambahan di galeri paket ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Box Tambah Foto Galeri Baru -->
        <div class="bg-gray-50/70 p-5 rounded-2xl border border-gray-200/80 space-y-4">
            <h4 class="font-bold text-sm text-primary flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-accent"></i>
                Tambah Foto ke Galeri
            </h4>

            <form action="{{ route('admin.rooms.upload_photos', $roomType->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Opsi A: Upload File dari Komputer/HP -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            <i class="fa-regular fa-folder-open text-accent mr-1"></i> Upload File Gambar (Bisa Pilih Banyak Sekaligus)
                        </label>
                        <input type="file" name="photos[]" multiple accept="image/*" 
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-light cursor-pointer">
                        <span class="text-[10px] text-gray-400 mt-1 block">Mendukung format JPG, PNG, WEBP (Maksimal 4MB per foto).</span>
                    </div>

                    <!-- Opsi B: Tambah via Tautan URL Online -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-link text-accent mr-1"></i> Atau Masukkan URL Gambar Online
                        </label>
                        <input type="url" name="photo_url" placeholder="https://images.unsplash.com/..." 
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-accent outline-none">
                        <span class="text-[10px] text-gray-400 mt-1 block">Gunakan jika foto tersimpan di Google Drive (direct), Unsplash, dll.</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-accent text-primary font-bold text-xs hover:bg-accent-dark hover:text-white transition-colors shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Upload Foto ke Galeri</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SEKSI 2: INFORMASI DAN SPESIFIKASI PAKET -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h3 class="font-display text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-sliders text-accent"></i>
                Informasi & Tarif Paket
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Ubah nama paket, harga sewa per malam, kapasitas orang, dan fasilitas.</p>
        </div>

        <form action="{{ route('admin.rooms.update_type', $roomType->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Ganti Thumbnail Langsung -->
            <div class="bg-amber-50/40 p-5 rounded-2xl border border-amber-200/80 flex flex-col sm:flex-row items-start gap-5">
                <div class="w-32 h-24 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200 shadow-xs">
                    <img id="thumbnail-preview" src="{{ $roomType->thumbnail }}" alt="Thumbnail Saat Ini" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 space-y-2">
                    <span class="block text-xs font-bold text-gray-800">Ubah Foto Thumbnail Utama (Cover Depan):</span>
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/*" onchange="previewThumbnail(event)" 
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white cursor-pointer">
                        <span class="text-xs text-gray-400 font-semibold">atau URL:</span>
                        <input type="url" name="thumbnail_url" value="{{ $roomType->thumbnail }}" placeholder="https://..." 
                               class="w-full px-3 py-1.5 rounded-xl border border-gray-200 text-xs focus:border-accent outline-none">
                    </div>
                    <span class="text-[10px] text-gray-400 block">Foto ini yang akan muncul pada kartu depan di halaman Beranda & Katalog.</span>
                </div>
            </div>

            <!-- Nama Paket -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Paket / Kamar *</label>
                <input type="text" name="nama" value="{{ old('nama', $roomType->nama) }}" required 
                       class="w-full p-3 rounded-2xl border border-gray-200 text-sm focus:border-accent outline-none font-semibold text-primary">
            </div>

            <!-- Tarif & Kapasitas -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tarif Dasar (per Malam) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs text-gray-400 font-bold">Rp</span>
                        <input type="number" name="harga_dasar" value="{{ old('harga_dasar', (int)$roomType->harga_dasar) }}" required 
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:border-accent outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Kapasitas Min (Orang) *</label>
                    <input type="number" name="kapasitas_min" value="{{ old('kapasitas_min', $roomType->kapasitas_min) }}" required 
                           class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Kapasitas Max (Orang) *</label>
                    <input type="number" name="kapasitas_max" value="{{ old('kapasitas_max', $roomType->kapasitas_max) }}" required 
                           class="w-full p-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:border-accent outline-none">
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap Paket</label>
                <textarea name="deskripsi" rows="3" 
                          class="w-full p-3 rounded-2xl border border-gray-200 text-xs focus:border-accent outline-none leading-relaxed">{{ old('deskripsi', $roomType->deskripsi) }}</textarea>
            </div>

            <!-- Fasilitas -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Daftar Fasilitas (Satu baris per fasilitas)</label>
                <textarea name="fasilitas_raw" rows="5" 
                          class="w-full p-3 rounded-2xl border border-gray-200 text-xs font-mono focus:border-accent outline-none">@if(is_array($roomType->fasilitas)){{ implode("\n", $roomType->fasilitas) }}@endif</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" {{ $roomType->is_active ? 'checked' : '' }} class="accent-primary rounded w-4 h-4">
                <label for="is_active" class="text-xs font-bold text-gray-700 cursor-pointer">Tampilkan paket ini di website publik (Status Aktif)</label>
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.rooms.index') }}" class="px-5 py-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-primary hover:bg-primary-light text-white text-xs font-bold shadow-md transition-all">
                    Simpan Perubahan Paket
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewThumbnail(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('thumbnail-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection
