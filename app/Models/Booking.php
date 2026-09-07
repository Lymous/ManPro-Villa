<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_booking',
        'created_by',
        'nama_tamu',
        'no_wa',
        'email',
        'check_in',
        'check_out',
        'jumlah_tamu',
        'sumber',
        'total_harga',
        'nominal_dp',
        'kode_unik',
        'nominal_transfer',
        'status',
        'hold_expires_at',
        'jumlah_reschedule',
        'catatan',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'hold_expires_at' => 'datetime',
        'total_harga' => 'decimal:2',
        'nominal_dp' => 'decimal:2',
        'nominal_transfer' => 'decimal:2',
        'kode_unik' => 'integer',
        'jumlah_tamu' => 'integer',
        'jumlah_reschedule' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function bookingAddOns()
    {
        return $this->hasMany(BookingAddOn::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class)->latest();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function getDurationNightsAttribute()
    {
        if ($this->check_in && $this->check_out) {
            return $this->check_in->diffInDays($this->check_out);
        }

        return 1;
    }

    public function getRemainingBalanceAttribute()
    {
        $paid = $this->payments()
            ->where('status', 'diverifikasi')
            ->sum('nominal');
        $rem = $this->total_harga - $paid;

        return max(0, $rem);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp '.number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedDpAttribute()
    {
        return 'Rp '.number_format($this->nominal_dp, 0, ',', '.');
    }

    public function getFormattedTransferAttribute()
    {
        return 'Rp '.number_format($this->nominal_transfer, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'dp_dibayar' => 'DP Terbayar',
            'lunas' => 'Lunas',
            'check_in' => 'Sedang Menginap (Check-In)',
            'selesai' => 'Selesai (Check-Out)',
            'batal' => 'Dibatalkan',
            'reschedule' => 'Rescheduled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-300',
            'dp_dibayar' => 'bg-blue-100 text-blue-800 border-blue-300',
            'lunas' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'check_in' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'selesai' => 'bg-gray-100 text-gray-800 border-gray-300',
            'batal' => 'bg-red-100 text-red-800 border-red-300',
            'reschedule' => 'bg-purple-100 text-purple-800 border-purple-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
