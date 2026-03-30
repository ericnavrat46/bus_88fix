<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    public function flashSale(): MorphOne
    {
        return $this->morphOne(FlashSale::class, 'target');
    }

    public function getActiveFlashSaleAttribute()
    {
        return $this->flashSale()->active()->first();
    }

    public function getFinalPriceAttribute()
    {
        $flash = $this->active_flash_sale;
        if (!$flash) return $this->price;

        if ($flash->discount_type === 'percentage') {
            return $this->price * (1 - ($flash->discount_value / 100));
        }

        return max(0, $this->price - $flash->discount_value);
    }
}
