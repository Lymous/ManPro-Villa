@extends('layouts.app')

@section('title', 'Tentang Kami - Villa Bukit Bunga Pacet Mojokerto')
@section('meta_description', 'Kenali lebih dalam Villa Bukit Bunga Pacet — villa keluarga & gathering terbaik di Mojokerto dengan kolam renang, aula rapat, dan kapasitas hingga 125 orang.')

@section('content')

{{-- HERO TENTANG KAMI --}}
<section class="relative luxury-gradient text-white overflow-hidden hero-pattern">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=2000&q=80"
             alt="Villa Bukit Bunga Pacet"
             class="w-full h-full object-cover object-center opacity-25 mix-blend-overlay filter brightness-90">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/70 via-primary/85 to-primary/95"></div>
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-accent/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 text-center">

        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-6">
            Tentang <span class="italic text-accent font-normal">Villa Bukit Bunga</span>
        </h1>
        <p class="text-white/70 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
            Destinasi villa keluarga dan gathering terpercaya di Pacet, Mojokerto — hadir dengan suasana pegunungan yang sejuk, fasilitas lengkap, dan pelayanan hangat sejak 2010.
        </p>

        <!-- Quick stats -->
        <div class="mt-10 inline-flex flex-wrap items-center justify-center gap-6 bg-white/8 backdrop-blur-md border border-white/15 rounded-2xl px-8 py-4">
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-white">15+</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Tahun Beroperasi</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-white">5.000+</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Tamu Puas</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-white">125</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Kapasitas Maks</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div class="text-center">
                <p class="font-display font-bold text-2xl text-accent">Pacet</p>
                <p class="text-white/55 text-[11px] uppercase tracking-wider">Mojokerto</p>
            </div>
        </div>
    </div>
</section>


{{-- PROFIL SINGKAT --}}
<section class="py-20 bg-cream">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-accent text-xs font-bold uppercase tracking-widest">Siapa Kami</span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-primary mt-3 mb-6 leading-snug">
                    Villa Keluarga & Rombongan <br>di Jantung Pegunungan Pacet
                </h2>
                <p class="text-gray-600 leading-relaxed mb-5">
                    <strong class="text-primary">Villa Bukit Bunga</strong> berdiri di kawasan wisata Pacet, Mojokerto — salah satu dataran tinggi paling sejuk di Jawa Timur. Kami menyediakan akomodasi eksklusif yang dirancang khusus untuk keluarga besar, rombongan sekolah, kampus, dan gathering perusahaan.
                </p>
                <p class="text-gray-600 leading-relaxed mb-5">
                    Dengan total <strong class="text-primary">9 paket villa</strong> yang mencakup kapasitas 6 hingga 125 orang, setiap tamu dapat menikmati privasi, kenyamanan, dan suasana alam yang memulihkan semangat.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Berlokasi strategis di <strong class="text-primary">Jln. Air Panas KM. 2, Gapuk, Pacet</strong> — hanya beberapa menit dari pemandian air panas terkenal di kawasan ini.
                </p>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=900&q=80"
                     alt="Suasana Villa Bukit Bunga Pacet"
                     class="w-full rounded-3xl shadow-2xl object-cover aspect-[4/3]">
                <div class="absolute -bottom-5 -left-5 bg-accent text-primary px-6 py-4 rounded-2xl shadow-xl">
                    <p class="font-display font-bold text-3xl">15+</p>
                    <p class="text-xs font-semibold uppercase tracking-wider">Tahun Beroperasi</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ANGKA KEBERHASILAN --}}
<section class="py-16 bg-white border-y border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="group">
                <div class="w-14 h-14 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mx-auto mb-4 group-hover:bg-accent group-hover:text-primary transition-all duration-300">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <p class="font-display font-bold text-3xl text-primary">5.000+</p>
                <p class="text-gray-500 text-sm mt-1">Tamu Puas</p>
            </div>
            <div class="group">
                <div class="w-14 h-14 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mx-auto mb-4 group-hover:bg-accent group-hover:text-primary transition-all duration-300">
                    <i class="fa-solid fa-house-chimney text-xl"></i>
                </div>
                <p class="font-display font-bold text-3xl text-primary">9 Paket</p>
                <p class="text-gray-500 text-sm mt-1">Pilihan Villa</p>
            </div>
            <div class="group">
                <div class="w-14 h-14 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mx-auto mb-4 group-hover:bg-accent group-hover:text-primary transition-all duration-300">
                    <i class="fa-solid fa-person-swimming text-xl"></i>
                </div>
                <p class="font-display font-bold text-3xl text-primary">3 Kolam</p>
                <p class="text-gray-500 text-sm mt-1">Kolam Renang</p>
            </div>
            <div class="group">
                <div class="w-14 h-14 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mx-auto mb-4 group-hover:bg-accent group-hover:text-primary transition-all duration-300">
                    <i class="fa-solid fa-people-group text-xl"></i>
                </div>
                <p class="font-display font-bold text-3xl text-primary">125 Org</p>
                <p class="text-gray-500 text-sm mt-1">Kapasitas Maks</p>
            </div>
        </div>
    </div>
</section>

{{-- FASILITAS UNGGULAN --}}
<section class="py-20 bg-cream">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-accent text-xs font-bold uppercase tracking-widest">Fasilitas</span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-primary mt-3">Apa yang Kami Sediakan</h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">Setiap fasilitas dirancang untuk memberikan pengalaman menginap yang nyaman, menyenangkan, dan tak terlupakan.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-person-swimming text-lg text-accent"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-primary mb-2">Kolam Renang Bersih</h3>
                <p class="text-gray-500 text-sm leading-relaxed">3 kolam renang terpisah untuk anak dan dewasa, bersih dan terawat setiap hari.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-volume-high text-lg text-accent"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-primary mb-2">Aula & Sound System</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Aula pertemuan luas dengan sound system profesional, cocok untuk gathering, seminar, dan LDKS.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-bed text-lg text-accent"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-primary mb-2">Kamar Tidur Nyaman</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Kamar ber-AC dengan kasur berkualitas, tersedia untuk 6 hingga 125 orang dalam berbagai konfigurasi.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-fire-burner text-lg text-accent"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-primary mb-2">Dapur & Area BBQ</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Dapur bersama yang lengkap dan area BBQ outdoor untuk pengalaman memasak bersama yang seru.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-parking text-lg text-accent"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-primary mb-2">Parkir Luas</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Area parkir yang luas mampu menampung bus dan kendaraan besar rombongan.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-wifi text-lg text-accent"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-primary mb-2">WiFi Tersedia</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Koneksi WiFi tersedia di area umum villa untuk kebutuhan komunikasi tamu.</p>
            </div>
        </div>
    </div>
</section>

{{-- VISI & MISI --}}
<section class="py-20 bg-primary text-white relative overflow-hidden hero-pattern">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-accent text-xs font-bold uppercase tracking-widest">Nilai Kami</span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold mt-3">Visi & Misi</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8">
                <div class="w-12 h-12 rounded-2xl bg-accent/20 text-accent flex items-center justify-center mb-5">
                    <i class="fa-solid fa-eye text-xl"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-accent mb-4">Visi</h3>
                <p class="text-white/75 leading-relaxed">
                    Menjadi destinasi villa keluarga dan gathering paling dipercaya di Jawa Timur, dengan standar kebersihan, keamanan, dan keramahan pelayanan yang konsisten di atas rata-rata industri.
                </p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8">
                <div class="w-12 h-12 rounded-2xl bg-accent/20 text-accent flex items-center justify-center mb-5">
                    <i class="fa-solid fa-bullseye text-xl"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-accent mb-4">Misi</h3>
                <ul class="text-white/75 leading-relaxed space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-accent text-xs mt-1.5"></i>
                        <span>Menyediakan fasilitas villa yang bersih, aman, dan nyaman untuk semua segmen tamu.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-accent text-xs mt-1.5"></i>
                        <span>Memberikan pelayanan responsif dan ramah sejak reservasi hingga check-out.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-accent text-xs mt-1.5"></i>
                        <span>Terus berinovasi dalam pengalaman wisata keluarga dan gathering di kawasan Pacet.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- LOKASI & KONTAK --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-accent text-xs font-bold uppercase tracking-widest">Lokasi</span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-primary mt-3 mb-6">
                    Mudah Dijangkau, <br>Tenang Tersembunyi
                </h2>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-sm">Alamat</p>
                            <p class="text-gray-500 text-sm mt-0.5">Jln. Air Panas KM. 2, Gapuk, Made, Kec. Pacet, Kabupaten Mojokerto, Jawa Timur</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-sm">WhatsApp Admin</p>
                            <a href="https://wa.me/6285808408835" target="_blank"
                               class="text-emerald-600 hover:text-emerald-700 font-medium text-sm mt-0.5 inline-block transition-colors">
                                0858 0840 8835
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-sm">Check-in / Check-out</p>
                            <p class="text-gray-500 text-sm mt-0.5">Check-in: 14.00 WIB &nbsp;|&nbsp; Check-out: 12.00 WIB</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-car"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-sm">Jarak dari Kota</p>
                            <p class="text-gray-500 text-sm mt-0.5">±45 menit dari Mojokerto Kota &nbsp;·&nbsp; ±1,5 jam dari Surabaya</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="https://maps.google.com/?q=Jln+Air+Panas+KM+2+Pacet+Mojokerto" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-primary text-white text-sm font-bold hover:bg-primary-light transition-all shadow-sm">
                        <i class="fa-solid fa-map-location-dot"></i> Lihat di Google Maps
                    </a>
                    <a href="https://wa.me/6285808408835?text=Halo%20Admin%20Villa%20Bukit%20Bunga%2C%20saya%20ingin%20bertanya%20tentang%20villa" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full border-2 border-emerald-500 text-emerald-600 text-sm font-bold hover:bg-emerald-50 transition-all">
                        <i class="fa-brands fa-whatsapp"></i> Hubungi Admin
                    </a>
                </div>
            </div>
            {{-- Google Maps Embed --}}
            <div class="rounded-3xl overflow-hidden shadow-xl border border-gray-100 h-80 lg:h-96">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.0534932752437!2d111.7403!3d-7.6519!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzknMDYuOCJTIDExMcKwNDQnMjUuMSJF!5e0!3m2!1sid!2sid!4v1694000000000!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Villa Bukit Bunga Pacet"></iframe>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-cream border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-primary mb-4">
            Siap Merencanakan <span class="italic text-accent">Liburan Impian</span>?
        </h2>
        <p class="text-gray-500 mb-8 text-sm leading-relaxed">
            Jelajahi 9 paket villa kami atau langsung hubungi admin untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('packages.index') }}"
                class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full bg-primary text-white font-bold text-sm hover:bg-primary-light transition-all shadow-md">
                <i class="fa-solid fa-bed"></i> Lihat Semua Paket
            </a>
            <a href="https://wa.me/6285808408835?text=Halo%20Admin%20Villa%20Bukit%20Bunga%2C%20saya%20ingin%20tanya%20informasi%20villa" target="_blank"
                class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full border-2 border-primary text-primary font-bold text-sm hover:bg-primary hover:text-white transition-all">
                <i class="fa-brands fa-whatsapp"></i> Tanya via WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection
