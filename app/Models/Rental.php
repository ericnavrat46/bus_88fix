<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_code',
        'user_id',
        'bus_id',
        'start_date',
        'end_date',
        'duration_days',
        'pickup_location',
        'destination',
        'purpose',
        'passenger_count',
        'contact_name',
        'contact_phone',
        'total_price',
        'approval_status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'snap_token',
        'midtrans_order_id',
        'admin_notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_price' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public static function generateRentalCode(): string
    {
        return 'RNT' . date('Ymd') . strtoupper(substr(uniqid(), -5));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function latestPayment()
    {
        return $this->morphOne(Payment::class, 'payable')->latestOfMany();
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
