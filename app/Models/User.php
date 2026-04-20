<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token',
        'role',
        'phone',
        'address',
        'google_id',
        'avatar',
        'otp',
        'expired_otp',
        'last_otp_sent_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'expired_otp' => 'datetime',
            'last_otp_sent_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function tourBookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function checkPendingPayments()
    {
        // Check pending bookings
        foreach ($this->bookings()->whereIn('payment_status', ['pending', 'unpaid'])->get() as $booking) {
            $booking->checkExpiration();
        }

        // Check pending rentals
        foreach ($this->rentals()->whereIn('payment_status', ['pending', 'unpaid'])->get() as $rental) {
            $rental->checkExpiration();
        }

        // Check pending tour bookings
        foreach ($this->tourBookings()->whereIn('payment_status', ['pending', 'unpaid'])->get() as $tBooking) {
            $tBooking->checkExpiration();
        }
    }
}