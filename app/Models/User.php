<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPenjaga(): bool
    {
        return $this->role === 'penjaga';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    public function customerBookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class, 'created_by');
    }

    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class, 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
