@extends('layouts.admin')

@section('title', 'Manajemen Paket & Kamar Fisik - Villa Bukit Bunga')
@section('page_title', 'Paket Villa & Unit Kamar Fisik')

@section('content')
<div class="space-y-10">
    <!-- Section 1: Paket Villa Bukit Bunga (Paket 1 - 9) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h3 class="font-display text-xl font-bold text-primary">Daftar Paket Villa Bukit Bunga</h3>
                <p class="text-xs text-gray-500 mt-0.5">Kelola tarif sewa per malam, spesifikasi kapasitas tamu, dan rincian fasilitas</p>
            </div>
            <div class="text-xs font-semibold text-gray-500 bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                Total: <strong class="text-primary">{{ $roomTypes->where('kategori', 'paket')->count() }} Paket Aktif</strong>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($roomTypes->where('kategori', 'paket') as $pkg)
            <div class="group bg-white rounded-3xl border border-gray-200/90 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                <!-- Thumbnail Cover with Badges -->
                <div class="relative h-44 bg-gray-100 overflow-hidden">
                    <img src="{{ $pkg->thumbnail ?? 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=600&q=80' }}" 
                         alt="{{ $pkg->nama }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                    <!-- Kapasitas Badge (Rapih & Jelas) -->
                    <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-white/95 text-gray-800 shadow-md backdrop-blur-sm">
                            <i class="fa-solid fa-users text-accent text-xs"></i>
                            <span>{{ $pkg->kapasitas_min }} – {{ $pkg->kapasitas_max }} Orang</span>
                        </div>

                        @if(str_contains(strtolower($pkg->nama), 'aula'))
                        <div class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[11px] font-bold bg-accent/90 text-primary shadow-md backdrop-blur-sm">
                            <i class="fa-solid fa-champagne-glasses text-[10px]"></i>
                            <span>Aula + Sound</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h4 class="font-display text-base font-bold text-primary group-hover:text-accent transition-colors line-clamp-2 leading-snug mb-2">
                            {{ $pkg->nama }}
                        </h4>
                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-3">
                            {{ $pkg->deskripsi }}
                        </p>

                        <!-- Fasilitas Preview Tags -->
                        <div class="flex flex-wrap gap-1.5">
                            @if(is_array($pkg->fasilitas))
                                @foreach(array_slice($pkg->fasilitas, 0, 2) as $f)
                                <span class="text-[10px] font-medium bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md border border-gray-100">
                                    ✓ {{ $f }}
                                </span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Price & Actions Footer -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold block">Tarif Sewa</span>
                            <span class="font-display text-base font-bold text-primary block leading-tight">
                                {{ $pkg->formatted_price }}
                                <span class="text-[10px] font-normal text-gray-400">/malam</span>
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full {{ $pkg->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="{{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}"></span>
                            <a href="{{ route('admin.rooms.edit_type', $pkg->id) }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-light text-white text-xs font-bold transition-all shadow-xs">
                                <i class="fa-solid fa-pen-to-square text-[10px] text-accent"></i>
                                <span>Edit Detail</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Section 2: Unit Kamar Fisik (rooms) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h3 class="font-display text-xl font-bold text-primary">Unit Kamar Fisik (Physical Rooms)</h3>
                <p class="text-xs text-gray-500 mt-0.5">Penetapan nomor kamar nyata saat tamu check-in (Standar, Deluxe, Kamar Besar)</p>
            </div>

            <!-- Form Tambah Unit Kamar Cepat -->
            <form action="{{ route('admin.rooms.store_room') }}" method="POST" class="flex flex-wrap items-center gap-2">
                @csrf
                <select name="room_type_id" required class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none">
                    @foreach($roomTypes as $rt)
                    <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                    @endforeach
                </select>
                <input type="text" name="nomor_kamar" required placeholder="No Kamar (cth: DLX-09)" class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs font-semibold focus:border-accent outline-none w-40">
                <input type="hidden" name="status" value="tersedia">
                <button type="submit" class="px-4 py-2 rounded-xl bg-accent text-primary text-xs font-bold hover:bg-accent-dark hover:text-white transition-colors shadow-xs">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Unit
                </button>
            </form>
        </div>

        <!-- Kamar Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($rooms as $r)
            <div class="p-4 rounded-2xl border border-gray-200/90 bg-white hover:border-accent/40 shadow-xs transition-all flex flex-col justify-between space-y-3">
                <div class="text-center">
                    <span class="font-mono text-lg font-bold text-primary block leading-none mb-1">{{ $r->nomor_kamar }}</span>
                    <span class="text-[10px] text-gray-500 block truncate font-medium" title="{{ $r->roomType->nama }}">
                        {{ $r->roomType->nama }}
                    </span>
                </div>

                <form action="{{ route('admin.rooms.update_room_status', $r->id) }}" method="POST">
                    @csrf
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full text-[11px] font-bold py-1.5 px-2 rounded-xl border outline-none cursor-pointer text-center
                            {{ $r->status === 'tersedia' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($r->status === 'terisi' ? 'bg-rose-50 text-rose-800 border-rose-200' : 'bg-amber-50 text-amber-800 border-amber-200') }}">
                        <option value="tersedia" {{ $r->status === 'tersedia' ? 'selected' : '' }}>• Tersedia</option>
                        <option value="terisi" {{ $r->status === 'terisi' ? 'selected' : '' }}>• Terisi</option>
                        <option value="pemeliharaan" {{ $r->status === 'pemeliharaan' ? 'selected' : '' }}>• Maintenance</option>
                    </select>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
