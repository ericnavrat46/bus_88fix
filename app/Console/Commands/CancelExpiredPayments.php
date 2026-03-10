<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use Illuminate\Console\Command;

class CancelExpiredPayments extends Command
{
    protected $signature   = 'payments:cancel-expired';
    protected $description = 'Cancel all pending payments that have not been paid within 2 hours';

    public function handle(): void
    {
        $expiredAt = now()->subHours(2);

        // --- Booking (tiket bus) -------------------------------------------------
        $bookings = Booking::where('payment_status', 'pending')
            ->where('created_at', '<=', $expiredAt)
            ->get();

        foreach ($bookings as $booking) {
            $booking->update(['payment_status' => 'cancelled']);
        }

        $bookingCount = $bookings->count();

        // --- Tour Booking --------------------------------------------------------
        $tourBookings = TourBooking::where('payment_status', 'pending')
            ->where('created_at', '<=', $expiredAt)
            ->get();

        foreach ($tourBookings as $tb) {
            $tb->update(['payment_status' => 'cancelled']);
        }

        $tourCount = $tourBookings->count();

        // --- Rental (charter) ----------------------------------------------------
        // Yang sudah di-approve admin tapi belum bayar (unpaid atau pending) > 2 jam
        $rentals = Rental::where('approval_status', 'approved')
            ->whereIn('payment_status', ['unpaid', 'pending'])
            ->where('updated_at', '<=', $expiredAt) // timer dihitung sejak di-approve (updated_at berubah saat approve)
            ->get();

        foreach ($rentals as $rental) {
            $rental->update(['payment_status' => 'cancelled']);
        }

        $rentalCount = $rentals->count();

        $total = $bookingCount + $tourCount + $rentalCount;

        $this->info("[" . now()->format('Y-m-d H:i:s') . "] Expired payments cancelled:");
        $this->line("  Booking  : {$bookingCount}");
        $this->line("  Tour     : {$tourCount}");
        $this->line("  Rental   : {$rentalCount}");
        $this->line("  Total    : {$total}");
    }
}
