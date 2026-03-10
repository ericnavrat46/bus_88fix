<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_date',
        'departure_time',
        'arrival_time',
        'price',
        'available_seats',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getBookedSeatsAttribute(): array
    {
        return $this->bookings()
            ->whereIn('payment_status', ['pending', 'paid'])
            ->with('passengers')
            ->get()
            ->pluck('passengers')
            ->flatten()
            ->pluck('seat_number')
            ->toArray();
    }

    public function getRemainingSeatsAttribute(): int
    {
        return $this->available_seats - count($this->booked_seats);
    }
}
