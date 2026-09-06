<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'room_type_id',
        'room_id',
        'jumlah_kamar',
        'harga_per_malam',
        'jumlah_malam',
        'subtotal',
    ];

    protected $casts = [
        'harga_per_malam' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'jumlah_kamar' => 'integer',
        'jumlah_malam' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
