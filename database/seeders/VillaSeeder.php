<?php

namespace Database\Seeders;

use App\Models\AddOn;
use App\Models\Policy;
use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VillaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@bukitbunga.com'],
            [
                'name' => 'Administrator Villa Bukit Bunga',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '085808408835',
            ]
        );

        $penjaga = User::firstOrCreate(
            ['email' => 'penjaga@bukitbunga.com'],
            [
                'name' => 'Pak Joko (Penjaga Villa)',
                'password' => Hash::make('password123'),
                'role' => 'penjaga',
                'is_active' => true,
                'phone' => '081234567890',
            ]
        );

        // 2. Policies
        $policies = [
            ['key' => 'dp_percentage', 'label' => 'Persentase Uang Muka (DP)', 'value' => '50'],
            ['key' => 'hold_duration_hours', 'label' => 'Batas Waktu Tahan Booking (Jam)', 'value' => '24'],
            ['key' => 'reschedule_deadline_days', 'label' => 'Batas Maksimal Pengajuan Reschedule (Hari sebelum Check-in)', 'value' => '7'],
            ['key' => 'max_reschedule_count', 'label' => 'Maksimal Kesempatan Reschedule', 'value' => '1'],
            ['key' => 'overtime_fee_per_hour', 'label' => 'Denda Keterlambatan Check-out (per Jam)', 'value' => '100000'],
            ['key' => 'check_in_time', 'label' => 'Waktu Check-In Standar', 'value' => '14:00 WIB'],
            ['key' => 'check_out_time', 'label' => 'Waktu Check-Out Standar', 'value' => '12:00 WIB'],
            ['key' => 'villa_contact_wa', 'label' => 'Nomor WhatsApp Resmi Villa', 'value' => '085808408835'],
            ['key' => 'villa_address', 'label' => 'Alamat Lengkap Villa', 'value' => 'Jln. Air Panas KM. 2, Gapuk, Made, Pacet, Mojokerto'],
            ['key' => 'manual_bank_name', 'label' => 'Nama Bank Transfer Manual', 'value' => 'Bank Central Asia (BCA)'],
            ['key' => 'manual_bank_account', 'label' => 'Nomor Rekening Bank Transfer Manual', 'value' => '0182736455'],
            ['key' => 'manual_bank_holder', 'label' => 'Atas Nama Rekening Bank', 'value' => 'Villa Bukit Bunga Pacet'],
        ];

        foreach ($policies as $p) {
            Policy::updateOrCreate(
                ['key' => $p['key']],
                [
                    'label' => $p['label'],
                    'value' => $p['value'],
                    'updated_by' => $admin->id,
                ]
            );
        }

        // 3. Add-Ons
        $addOns = [
            [
                'nama' => 'Extra Bed + Selimut',
                'harga' => 50000,
                'satuan' => 'per_malam',
                'deskripsi' => 'Kasur busa empuk lengkap bantal dan selimut hangat untuk tambahan kenyamanan tidur.',
                'icon' => 'bed',
            ],
            [
                'nama' => 'Paket Alat & Arang BBQ',
                'harga' => 85000,
                'satuan' => 'paket',
                'deskripsi' => 'Alat panggangan lengkap, penjepit, kipas, dan 2 karung arang batok kelapa siap pakai.',
                'icon' => 'flame',
            ],
            [
                'nama' => 'Paket Sarapan Pagi Pacet (per Orang)',
                'harga' => 25000,
                'satuan' => 'per_orang',
                'deskripsi' => 'Nasi Goreng Spesial Pacet / Nasi Uduk Komplit hangat dengan teh manis dan kerupuk.',
                'icon' => 'utensils',
            ],
            [
                'nama' => 'Sound System Outdoor + 2 Mic Wireless',
                'harga' => 150000,
                'satuan' => 'per_malam',
                'deskripsi' => 'Speaker aktif portable bertenaga tinggi untuk acara kebersamaan atau senam pagi di halaman.',
                'icon' => 'music',
            ],
            [
                'nama' => 'Late Check-out s/d Pukul 15:00',
                'harga' => 150000,
                'satuan' => 'paket',
                'deskripsi' => 'Perpanjangan waktu bersantai hingga sore hari (tergantung ketersediaan jadwal berikutnya).',
                'icon' => 'clock',
            ],
        ];

        foreach ($addOns as $ao) {
            AddOn::updateOrCreate(['nama' => $ao['nama']], $ao);
        }

        // 4. Room Types (Paket 1 s.d 9 dan Satuan Kamar)
        $standardFacilities = [
            'Swimming Pool (Kolam Renang)',
            'Wifi Berkecepatan Tinggi',
            'Dapur Lengkap & Alat Masak',
            'Parkir Luas Muat Bis & Mobil',
            'Kamar Mandi Air Hangat / Dingin',
            'Dekat Wisata Air Panas Pacet',
        ];

        $paketData = [
            [
                'nama' => 'Paket 1 - Family Retreat (1 Kamar Besar)',
                'slug' => 'paket-1-family-retreat',
                'kategori' => 'paket',
                'kapasitas_min' => 10,
                'kapasitas_max' => 18,
                'harga_dasar' => 500000,
                'harga_orang_tambahan' => 35000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Pilihan ekonomis dan nyaman untuk keluarga inti atau kelompok kecil: 1 Kamar Besar (KM Dalam), tanpa penggunaan aula utama. Akses penuh ke kolam renang, dapur, wifi, dan area santai.',
                'fasilitas' => array_merge(['1 Kamar Tidur Besar Luas (KM Dalam)', 'Tanpa Aula Utama'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 2 - Gathering Mini (4 Kamar Standar + Aula)',
                'slug' => 'paket-2-gathering-mini',
                'kategori' => 'paket',
                'kapasitas_min' => 25,
                'kapasitas_max' => 40,
                'harga_dasar' => 1250000,
                'harga_orang_tambahan' => 40000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Sangat cocok untuk rombongan reuni atau arisan keluarga: 4 Kamar tipe Standar (KM Dalam), Aula serbaguna lengkap dengan sound system dan meja kursi rapat, ruang makan, dapur, kolam renang, sebagian tidur di aula.',
                'fasilitas' => array_merge(['4 Kamar Standar (KM Dalam)', 'Aula Serbaguna + Sound System & Meja Rapat', 'Ruang Makan & Dapur Bersama', 'Sebagian Tidur di Aula'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 3 - Gathering Family Plus (5 Kamar Standar + Aula)',
                'slug' => 'paket-3-gathering-family-plus',
                'kategori' => 'paket',
                'kapasitas_min' => 25,
                'kapasitas_max' => 50,
                'harga_dasar' => 1400000,
                'harga_orang_tambahan' => 40000,
                'jumlah_unit' => 1,
                'deskripsi' => '5 Kamar Tipe Standar dengan fasilitas kamar mandi dalam. Dilengkapi aula pertemuan dengan sound system, meja kursi rapat, dapur, kolam renang, sebagian peserta dapat tidur di aula.',
                'fasilitas' => array_merge(['5 Kamar Standar (KM Dalam)', 'Aula Serbaguna + Sound System', 'Sebagian Tidur di Aula'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 4 - Deluxe Comfort (4 Kamar Deluxe + Aula)',
                'slug' => 'paket-4-deluxe-comfort',
                'kategori' => 'paket',
                'kapasitas_min' => 25,
                'kapasitas_max' => 40,
                'harga_dasar' => 1600000,
                'harga_orang_tambahan' => 45000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Kenyamanan kamar tipe Deluxe yang lebih luas dan modern: 4 Kamar Deluxe (KM Dalam), Aula pertemuan lengkap sound system & proyektor/meja rapat, dapur, kolam renang, sebagian tidur di aula.',
                'fasilitas' => array_merge(['4 Kamar Deluxe Premium (KM Dalam)', 'Aula Pertemuan Ber-AC + Sound System', 'Sebagian Tidur di Aula'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 5 - Deluxe Premium (5 Kamar Deluxe + Aula)',
                'slug' => 'paket-5-deluxe-premium',
                'kategori' => 'paket',
                'kapasitas_min' => 25,
                'kapasitas_max' => 40,
                'harga_dasar' => 1850000,
                'harga_orang_tambahan' => 45000,
                'jumlah_unit' => 1,
                'deskripsi' => '5 Kamar Tipe Deluxe yang mewah dan nyaman. Semua rombongan tertampung dengan aula rapat eksklusif, sound system, ruang makan, dapur lengkap, dan kolam renang bersih view pegunungan.',
                'fasilitas' => array_merge(['5 Kamar Deluxe Premium (KM Dalam)', 'Aula Pertemuan Megah + Sound System', 'Semua Tidur Nyaman di Kamar'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 6 - Big Group Harmony (5 Standar + 5 Deluxe + Aula)',
                'slug' => 'paket-6-big-group-harmony',
                'kategori' => 'paket',
                'kapasitas_min' => 40,
                'kapasitas_max' => 60,
                'harga_dasar' => 2250000,
                'harga_orang_tambahan' => 50000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Pilihan utama kegiatan kantor & rombongan besar: Total 10 Kamar (5 Kamar Standar + 5 Kamar Deluxe). Bisa tidur di ranjang kamar semua tanpa harus berdesakan di aula! Termasuk aula pertemuan besar.',
                'fasilitas' => array_merge(['10 Kamar (5 Standar + 5 Deluxe KM Dalam)', 'Bisa Tidur di Kamar Semua', 'Aula Besar + Sound System Lengkap'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 7 - Grand Summit (5 Standar + 5 Deluxe + 1 Kamar Besar + Aula)',
                'slug' => 'paket-7-grand-summit',
                'kategori' => 'paket',
                'kapasitas_min' => 50,
                'kapasitas_max' => 65,
                'harga_dasar' => 2750000,
                'harga_orang_tambahan' => 50000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Kombinasi 11 Kamar: 5 Kamar Standar, 5 Kamar Deluxe, dan 1 Kamar Besar VIP. Seluruh peserta dapat tidur di kamar. Aula pertemuan luas dengan sound system rapat, ruang makan, dan kolam renang.',
                'fasilitas' => array_merge(['11 Kamar (5 Standar + 5 Deluxe + 1 Besar)', 'Bisa Tidur di Kamar Semua', 'Aula Kapasitas 80 Orang + Sound System'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 8 - Corporate Exclusives (5 Standar + 8 Deluxe + Aula)',
                'slug' => 'paket-8-corporate-exclusives',
                'kategori' => 'paket',
                'kapasitas_min' => 50,
                'kapasitas_max' => 75,
                'harga_dasar' => 3250000,
                'harga_orang_tambahan' => 50000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Total 13 Kamar: 5 Kamar Standar dan 8 Kamar Deluxe. Aula rapat luas dengan fasilitas sound system, meja kursi rapat lengkap, ruang makan terbuka, dapur luas, dan kolam renang.',
                'fasilitas' => array_merge(['13 Kamar (5 Standar + 8 Deluxe)', 'Aula Rapat Luas + Sound System Penuh', 'Kapasitas s/d 75 Orang'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'nama' => 'Paket 9 - Full Villa Royalty (5 Standar + 8 Deluxe + 1 Kamar Besar + Aula)',
                'slug' => 'paket-9-full-villa-royalty',
                'kategori' => 'paket',
                'kapasitas_min' => 75,
                'kapasitas_max' => 125,
                'harga_dasar' => 3750000,
                'harga_orang_tambahan' => 50000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Sewa seluruh area Villa Bukit Bunga secara privat total! Membuka seluruh 14 kamar (5 Standar + 8 Deluxe + 1 Kamar Besar VIP). Kapasitas s/d 125 orang. Cocok untuk LDKS sekolah/kampus, gathering akbar, atau pernikahan keluarga.',
                'fasilitas' => array_merge(['Sewa Seluruh Villa (14 Kamar Lengkap)', 'Kapasitas Akbar 75-125 Orang', 'Aula Akbar + Sound System Lengkap', 'Privat Total Tanpa Tamu Lain'], $standardFacilities),
                'thumbnail' => 'https://images.unsplash.com/photo-1512915922686-57c11dde9b6b?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        // Sample photos collection for galleries
        $gallerySamples = [
            'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1000&q=80', // pool
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1000&q=80', // bedroom
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1000&q=80', // bedroom 2
            'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1000&q=80', // dining / hall
            'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1000&q=80', // lounge / view
        ];

        foreach ($paketData as $p) {
            $roomType = RoomType::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );

            // Seed 4 photos for each package
            foreach ($gallerySamples as $idx => $photoUrl) {
                RoomPhoto::firstOrCreate(
                    [
                        'room_type_id' => $roomType->id,
                        'file_path' => $photoUrl,
                    ],
                    [
                        'caption' => 'Sudut Villa Bukit Bunga - ' . ($idx + 1),
                        'urutan' => $idx + 1,
                        'is_primary' => $idx === 0,
                    ]
                );
            }
        }

        // Master Room Types for Physical Rooms:
        $masterStandar = RoomType::firstOrCreate(
            ['slug' => 'kamar-standar'],
            [
                'nama' => 'Kamar Tipe Standar (KM Dalam)',
                'kategori' => 'kamar',
                'kapasitas_min' => 2,
                'kapasitas_max' => 4,
                'harga_dasar' => 250000,
                'harga_orang_tambahan' => 30000,
                'jumlah_unit' => 5,
                'deskripsi' => 'Kamar tidur tipe standar dengan kamar mandi dalam yang bersih dan nyaman.',
                'fasilitas' => ['Kamar Mandi Dalam', 'Double Bed / Twin Bed', 'Wifi'],
                'thumbnail' => $gallerySamples[1],
            ]
        );

        $masterDeluxe = RoomType::firstOrCreate(
            ['slug' => 'kamar-deluxe'],
            [
                'nama' => 'Kamar Tipe Deluxe (KM Dalam)',
                'kategori' => 'kamar',
                'kapasitas_min' => 2,
                'kapasitas_max' => 5,
                'harga_dasar' => 350000,
                'harga_orang_tambahan' => 35000,
                'jumlah_unit' => 8,
                'deskripsi' => 'Kamar tidur deluxe lebih lapang dengan pemandangan asri pegunungan Pacet.',
                'fasilitas' => ['Kamar Mandi Dalam Air Hangat', 'King Bed Premium', 'Smart TV / Wifi'],
                'thumbnail' => $gallerySamples[2],
            ]
        );

        $masterBesar = RoomType::firstOrCreate(
            ['slug' => 'kamar-besar'],
            [
                'nama' => 'Kamar Tipe Besar Family (KM Dalam)',
                'kategori' => 'kamar',
                'kapasitas_min' => 10,
                'kapasitas_max' => 18,
                'harga_dasar' => 500000,
                'harga_orang_tambahan' => 35000,
                'jumlah_unit' => 1,
                'deskripsi' => 'Kamar berukuran sangat luas dapat menampung hingga belasan anggota keluarga dalam satu ruangan hangat.',
                'fasilitas' => ['Kamar Mandi Dalam', 'Multiple Bed System', 'Wifi', 'Dapur Dekat'],
                'thumbnail' => $gallerySamples[0],
            ]
        );

        // 5. Seed Physical Rooms
        // 5 Kamar Standar
        for ($i = 1; $i <= 5; $i++) {
            Room::firstOrCreate(
                ['nomor_kamar' => sprintf('STD-%02d', $i)],
                [
                    'room_type_id' => $masterStandar->id,
                    'status' => 'tersedia',
                    'catatan' => 'Kamar Standar Lantai 1 / 2',
                ]
            );
        }

        // 8 Kamar Deluxe
        for ($i = 1; $i <= 8; $i++) {
            Room::firstOrCreate(
                ['nomor_kamar' => sprintf('DLX-%02d', $i)],
                [
                    'room_type_id' => $masterDeluxe->id,
                    'status' => 'tersedia',
                    'catatan' => 'Kamar Deluxe View Kolam & Gunung',
                ]
            );
        }

        // 1 Kamar Besar
        Room::firstOrCreate(
            ['nomor_kamar' => 'BSR-01'],
            [
                'room_type_id' => $masterBesar->id,
                'status' => 'tersedia',
                'catatan' => 'Kamar Besar Utama Kapasitas 18 Orang',
            ]
        );
    }
}
