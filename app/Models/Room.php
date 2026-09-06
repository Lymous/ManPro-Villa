<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'nomor_kamar',
        'status',
        'catatan',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class);
    }
}
