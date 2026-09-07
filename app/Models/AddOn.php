<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
        'satuan',
        'deskripsi',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function bookingAddOns()
    {
        return $this->hasMany(BookingAddOn::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }
}
