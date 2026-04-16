<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'tour_package_id',
        'travel_date',
        'passenger_count',
        'total_price',
        'payment_status',
        'payment_method',
        'payment_proof',
        'snap_token',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'total_price' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public static function generateBookingCode(): string
    {
        return 'TRP' . date('Ymd') . strtoupper(substr(uniqid(), -5));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tourPackage()
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
