<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'verified_by',
        'jenis',
        'metode_pembayaran',
        'nominal',
        'file_bukti',
        'tanggal_transfer',
        'status',
        'tripay_reference',
        'tripay_pay_code',
        'tripay_checkout_url',
        'tripay_channel',
        'tripay_status',
        'verified_at',
        'payload',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_transfer' => 'date',
        'verified_at' => 'datetime',
        'payload' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'menunggu_konfirmasi' => 'bg-amber-100 text-amber-800 border-amber-300',
            'diverifikasi' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'ditolak' => 'bg-red-100 text-red-800 border-red-300',
            'kadaluarsa' => 'bg-gray-100 text-gray-800 border-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
