<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    public function uploadBookingProof(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($booking->payment_proof) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs/bookings', 'public');

        $booking->update([
            'payment_proof' => $path,
            'payment_method' => 'manual',
            'payment_status' => 'pending' // stays pending until admin approves
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function uploadRentalProof(Request $request, Rental $rental)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($rental->payment_proof) {
            Storage::disk('public')->delete($rental->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs/rentals', 'public');

        $rental->update([
            'payment_proof' => $path,
            'payment_method' => 'manual',
            'payment_status' => 'pending'
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function uploadTourProof(Request $request, TourBooking $booking)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($booking->payment_proof) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs/tours', 'public');

        $booking->update([
            'payment_proof' => $path,
            'payment_method' => 'manual',
            'payment_status' => 'pending'
        ]);

        return back()->with('success', 'Bukti pembayaran paket wisata berhasil diunggah. Menunggu verifikasi admin.');
    }
}
