<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type      = $request->input('type', 'booking');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        $data    = collect();
        $summary = [];

        if ($type === 'booking') {
            $data = Booking::with(['user', 'schedule.route', 'schedule.bus'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();

            $summary = [
                'total'    => $data->count(),
                'paid'     => $data->where('payment_status', 'paid')->count(),
                'pending'  => $data->where('payment_status', 'pending')->count(),
                'cancelled' => $data->whereIn('payment_status', ['cancelled', 'expired', 'refunded'])->count(),
                'revenue'  => $data->where('payment_status', 'paid')->sum('total_price'),
            ];
        } elseif ($type === 'rental') {
            $data = Rental::with(['user', 'bus'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();

            $summary = [
                'total'    => $data->count(),
                'approved' => $data->where('approval_status', 'approved')->count(),
                'pending'  => $data->where('approval_status', 'pending')->count(),
                'rejected' => $data->where('approval_status', 'rejected')->count(),
                'revenue'  => $data->where('payment_status', 'paid')->sum('total_price'),
            ];
        } elseif ($type === 'tour') {
            $data = TourBooking::with(['user', 'tourPackage'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();

            $summary = [
                'total'   => $data->count(),
                'paid'    => $data->where('payment_status', 'paid')->count(),
                'pending' => $data->where('payment_status', 'pending')->count(),
                'revenue' => $data->where('payment_status', 'paid')->sum('total_price'),
            ];
        }

        return view('admin.reports.index', compact('data', 'summary', 'type', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        $type      = $request->input('type', 'booking');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        $data    = collect();
        $summary = [];

        if ($type === 'booking') {
            $data = Booking::with(['user', 'schedule.route', 'schedule.bus'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();

            $summary = [
                'total'    => $data->count(),
                'paid'     => $data->where('payment_status', 'paid')->count(),
                'pending'  => $data->where('payment_status', 'pending')->count(),
                'cancelled' => $data->whereIn('payment_status', ['cancelled', 'expired', 'refunded'])->count(),
                'revenue'  => $data->where('payment_status', 'paid')->sum('total_price'),
            ];
        } elseif ($type === 'rental') {
            $data = Rental::with(['user', 'bus'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();

            $summary = [
                'total'    => $data->count(),
                'approved' => $data->where('approval_status', 'approved')->count(),
                'pending'  => $data->where('approval_status', 'pending')->count(),
                'rejected' => $data->where('approval_status', 'rejected')->count(),
                'revenue'  => $data->where('payment_status', 'paid')->sum('total_price'),
            ];
        } elseif ($type === 'tour') {
            $data = TourBooking::with(['user', 'tourPackage'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();

            $summary = [
                'total'   => $data->count(),
                'paid'    => $data->where('payment_status', 'paid')->count(),
                'pending' => $data->where('payment_status', 'pending')->count(),
                'revenue' => $data->where('payment_status', 'paid')->sum('total_price'),
            ];
        }

        return view('admin.reports.print', compact('data', 'summary', 'type', 'startDate', 'endDate'));
    }
}
