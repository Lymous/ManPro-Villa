<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'aksi',
        'nilai_lama',
        'nilai_baru',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(int $bookingId, string $aksi, ?string $nilaiLama = null, ?string $nilaiBaru = null, ?int $userId = null)
    {
        return static::create([
            'booking_id' => $bookingId,
            'user_id' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => $nilaiBaru,
        ]);
    }
}
