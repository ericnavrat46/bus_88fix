<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use App\Models\Payment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'tour_package' => 'App\Models\TourPackage',
            'schedule' => 'App\Models\Schedule',
            'rental' => 'App\Models\Rental',
        ]);

        // Share transaction notification counts with admin sidebar
        View::composer('layouts.admin', function ($view) {
            $view->with([
                'newBookingCount' => Booking::where('payment_status', 'pending')->count(),
                'newRentalCount' => Rental::where('approval_status', 'pending')->count(),
                'newTourCount' => TourBooking::where('payment_status', 'pending')->count(),
                'newPaymentCount' => Payment::where('status', 'pending')->count(),
            ]);
        });
    }
}
