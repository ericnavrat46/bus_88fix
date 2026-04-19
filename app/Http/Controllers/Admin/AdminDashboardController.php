<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Rental;
use App\Models\TourBooking;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $revenueBooking = Booking::where('payment_status', 'paid')->sum('total_price');
        $revenueRental  = Rental::where('payment_status', 'paid')->sum('total_price');
        $revenueTour    = TourBooking::where('payment_status', 'paid')->sum('total_price');

        $stats = [
            'total_revenue'   => $revenueBooking + $revenueRental + $revenueTour,
            'total_bookings'  => Booking::count(),
            'total_rentals'   => Rental::count(),
            'pending_rentals' => Rental::where('approval_status', 'pending')->count(),
            'total_buses'     => Bus::count(),
            'active_buses'    => Bus::where('status', 'active')->count(),
            'total_routes'    => Route::where('status', 'active')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_tours'     => TourBooking::count(),
        ];

        $recentBookings = Booking::with(['user', 'schedule.route'])
            ->latest()
            ->limit(5)
            ->get();

        $recentRentals = Rental::with(['user', 'bus'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentRentals'));
    }

    public function markNotificationsRead()
    {
        \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
