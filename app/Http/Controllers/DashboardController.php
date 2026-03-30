<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $bookings = Booking::with(['schedule.bus', 'schedule.route', 'passengers'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $rentals = Rental::with('bus')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $tourBookings = TourBooking::with('tourPackage')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.index', compact('bookings', 'rentals', 'tourBookings'));
    }

    public function bookingDetail(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Auto update status if expired
        $booking->checkExpiration();

        $booking->load(['schedule.bus', 'schedule.route', 'passengers', 'latestPayment']);

        return view('dashboard.booking-detail', compact('booking'));
    }

    public function rentalDetail(Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        $rental->load(['bus', 'latestPayment']);

        return view('dashboard.rental-detail', compact('rental'));
    }

    public function tourDetail(TourBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['tourPackage', 'payments']);

        return view('dashboard.tour-detail', compact('booking'));
    }
}
