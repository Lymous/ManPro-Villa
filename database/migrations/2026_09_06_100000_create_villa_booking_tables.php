<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. room_types
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->enum('kategori', ['paket', 'kamar'])->default('paket');
            $table->integer('kapasitas_min')->default(1);
            $table->integer('kapasitas_max')->default(20);
            $table->decimal('harga_dasar', 12, 2);
            $table->decimal('harga_orang_tambahan', 12, 2)->default(0);
            $table->integer('jumlah_unit')->default(1);
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. room_photos
        Schema::create('room_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 3. rooms
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->string('nomor_kamar');
            $table->enum('status', ['tersedia', 'terisi', 'pemeliharaan'])->default('tersedia');
            $table->string('catatan')->nullable();
            $table->timestamps();
        });

        // 4. add_ons
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('harga', 12, 2);
            $table->enum('satuan', ['per_malam', 'per_pcs', 'per_orang', 'paket'])->default('per_malam');
            $table->string('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. policies
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->text('value');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 6. bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_tamu');
            $table->string('no_wa');
            $table->string('email')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('jumlah_tamu');
            $table->enum('sumber', ['website', 'whatsapp', 'instagram', 'offline'])->default('website');
            $table->decimal('total_harga', 12, 2);
            $table->decimal('nominal_dp', 12, 2)->default(0);
            $table->integer('kode_unik')->default(0);
            $table->decimal('nominal_transfer', 12, 2);
            $table->enum('status', [
                'menunggu_pembayaran',
                'dp_dibayar',
                'lunas',
                'check_in',
                'selesai',
                'batal',
                'reschedule'
            ])->default('menunggu_pembayaran');
            $table->dateTime('hold_expires_at')->nullable();
            $table->integer('jumlah_reschedule')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 7. booking_rooms
        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->integer('jumlah_kamar')->default(1);
            $table->decimal('harga_per_malam', 12, 2);
            $table->integer('jumlah_malam')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 8. booking_add_ons
        Schema::create('booking_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('add_on_id')->constrained('add_ons')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 9. payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis', ['dp', 'pelunasan', 'denda', 'pembayaran_penuh'])->default('dp');
            $table->string('metode_pembayaran')->default('tripay'); // 'tripay', 'transfer_manual'
            $table->decimal('nominal', 12, 2);
            $table->string('file_bukti')->nullable();
            $table->date('tanggal_transfer')->nullable();
            $table->enum('status', ['menunggu_konfirmasi', 'diverifikasi', 'ditolak', 'kadaluarsa'])->default('menunggu_konfirmasi');
            $table->string('tripay_reference')->nullable()->index();
            $table->string('tripay_pay_code')->nullable();
            $table->string('tripay_checkout_url')->nullable();
            $table->string('tripay_channel')->nullable();
            $table->string('tripay_status')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        // 10. blocked_dates
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('alasan', ['pemeliharaan', 'pribadi', 'libur'])->default('pemeliharaan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 11. booking_logs
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aksi');
            $table->text('nilai_lama')->nullable();
            $table->text('nilai_baru')->nullable();
            $table->timestamps();
        });

        // 12. notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->enum('jenis', ['wa_konfirmasi', 'wa_reminder_h1', 'wa_batal', 'wa_reschedule', 'wa_lunas']);
            $table->string('no_tujuan');
            $table->text('pesan')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('booking_logs');
        Schema::dropIfExists('blocked_dates');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_add_ons');
        Schema::dropIfExists('booking_rooms');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('policies');
        Schema::dropIfExists('add_ons');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_photos');
        Schema::dropIfExists('room_types');
    }
};
