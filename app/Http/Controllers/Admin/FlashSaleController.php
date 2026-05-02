<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\TourPackage;
use App\Models\Schedule;
use App\Models\Rental;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Events\FlashSaleCreated;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::latest()->paginate(10);
        return view('admin.flash_sales.index', compact('flashSales'));
    }

    public function create()
    {
        $tourPackages = TourPackage::all();
        $schedules = Schedule::with(['bus', 'route'])->get();
        return view('admin.flash_sales.create', compact('tourPackages', 'schedules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'target_type' => 'required|string',
            'target_id' => 'required|integer',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        $flashSale = FlashSale::create($data);

        // Broadcast the event
        broadcast(new FlashSaleCreated($flashSale))->toOthers();

        // NOTIFIKASI FLASH SALE

        $discount = $flashSale->discount_type === 'percentage'
            ? $flashSale->discount_value . '% OFF — Kuota terbatas!'
            : 'Diskon Rp ' . number_format($flashSale->discount_value, 0, ',', '.') . ' — Kuota terbatas!';

        $title   = '🔥 Flash Sale: ' . $flashSale->title;
        $message = $discount;

        // Ambil semua user yang punya FCM token
        $users = User::whereNotNull('fcm_token')->get();

        foreach ($users as $user) {
            
            \App\Helpers\NotificationHelper::send(
                $user->id,
                $title,
                $message,
                'flash_sale'
            );

            Notification::create([
                'user_id' => $user->id,
                'title'   => $title,
                'message' => $message,
                'type'    => 'flash_sale',
                'data'    => json_encode(['flash_sale_id' => $flashSale->id]),
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale created successfully.');
    }

    public function edit(FlashSale $flashSale)
    {
        $tourPackages = TourPackage::all();
        $schedules = Schedule::with(['bus', 'route'])->get();
        return view('admin.flash_sales.edit', compact('flashSale', 'tourPackages', 'schedules'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'target_type' => 'required|string',
            'target_id' => 'required|integer',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        $flashSale->update($data);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale updated successfully.');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale::destroy($flashSale->id);
        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale deleted successfully.');
    }
}