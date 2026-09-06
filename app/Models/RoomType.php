<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'kategori',
        'kapasitas_min',
        'kapasitas_max',
        'harga_dasar',
        'harga_orang_tambahan',
        'jumlah_unit',
        'deskripsi',
        'fasilitas',
        'thumbnail',
        'is_active',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'is_active' => 'boolean',
        'harga_dasar' => 'decimal:2',
        'harga_orang_tambahan' => 'decimal:2',
    ];

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class)->orderBy('urutan');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(RoomPhoto::class)->where('is_primary', true);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_dasar, 0, ',', '.');
    }
}
