<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'booking_id',
        'tour_booking_id',
        'user_id',
        'refund_amount',
        'reason',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'admin_notes',
        'processed_at'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tourBooking()
    {
        return $this->belongsTo(TourBooking::class, 'tour_booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
