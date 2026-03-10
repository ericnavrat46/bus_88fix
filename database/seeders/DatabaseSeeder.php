<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bus88.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Customer User
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081298765432',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081312345678',
        ]);

        // Buses
        $buses = [
            ['name' => 'Garuda Merah', 'code' => 'GM01', 'type' => 'eksekutif', 'capacity' => 32, 'plate_number' => 'B 1234 ABC', 'facilities' => 'AC,WiFi,USB Charger,Reclining Seat,Toilet', 'status' => 'active'],
            ['name' => 'Nusantara Jaya', 'code' => 'NJ01', 'type' => 'eksekutif', 'capacity' => 40, 'plate_number' => 'B 5678 DEF', 'facilities' => 'AC,WiFi,USB Charger,Reclining Seat', 'status' => 'active'],
            ['name' => 'Putra Mandiri', 'code' => 'PM01', 'type' => 'ekonomi', 'capacity' => 44, 'plate_number' => 'D 9012 GHI', 'facilities' => 'AC,USB Charger', 'status' => 'active'],
            ['name' => 'Merdeka Express', 'code' => 'ME01', 'type' => 'eksekutif', 'capacity' => 36, 'plate_number' => 'B 3456 JKL', 'facilities' => 'AC,WiFi,USB Charger,Reclining Seat,TV,Snack', 'status' => 'active'],
            ['name' => 'Sinar Harapan', 'code' => 'SH01', 'type' => 'ekonomi', 'capacity' => 48, 'plate_number' => 'D 7890 MNO', 'facilities' => 'AC', 'status' => 'active'],
            ['name' => 'Rajawali Sakti', 'code' => 'RS01', 'type' => 'eksekutif', 'capacity' => 30, 'plate_number' => 'B 1122 PQR', 'facilities' => 'AC,WiFi,USB Charger,Reclining Seat,Toilet,TV', 'status' => 'maintenance'],
        ];

        foreach ($buses as $busData) {
            Bus::create($busData);
        }

        // Routes
        $routes = [
            ['origin' => 'Jakarta', 'destination' => 'Bandung', 'distance' => 150, 'duration' => 180, 'base_price' => 85000],
            ['origin' => 'Jakarta', 'destination' => 'Semarang', 'distance' => 450, 'duration' => 420, 'base_price' => 200000],
            ['origin' => 'Jakarta', 'destination' => 'Surabaya', 'distance' => 780, 'duration' => 720, 'base_price' => 350000],
            ['origin' => 'Bandung', 'destination' => 'Jakarta', 'distance' => 150, 'duration' => 180, 'base_price' => 85000],
            ['origin' => 'Bandung', 'destination' => 'Semarang', 'distance' => 350, 'duration' => 330, 'base_price' => 175000],
            ['origin' => 'Semarang', 'destination' => 'Surabaya', 'distance' => 350, 'duration' => 300, 'base_price' => 150000],
            ['origin' => 'Surabaya', 'destination' => 'Jakarta', 'distance' => 780, 'duration' => 720, 'base_price' => 350000],
            ['origin' => 'Jakarta', 'destination' => 'Yogyakarta', 'distance' => 530, 'duration' => 480, 'base_price' => 250000],
        ];

        foreach ($routes as $routeData) {
            Route::create($routeData);
        }

        // Schedules (next 7 days)
        $busIds = Bus::where('status', 'active')->pluck('id')->toArray();
        $routeList = Route::all();

        for ($day = 0; $day < 7; $day++) {
            $date = now()->addDays($day)->format('Y-m-d');

            foreach ($routeList as $route) {
                $busId = $busIds[array_rand($busIds)];
                $bus = Bus::find($busId);

                $departures = ['06:00', '08:30', '13:00', '18:00', '21:00'];
                $selectedDepartures = array_rand(array_flip($departures), rand(2, 3));
                if (!is_array($selectedDepartures)) $selectedDepartures = [$selectedDepartures];

                foreach ($selectedDepartures as $depTime) {
                    $arrivalMinutes = $route->duration;
                    $depCarbon = \Carbon\Carbon::parse($depTime);
                    $arrTime = $depCarbon->copy()->addMinutes($arrivalMinutes)->format('H:i');

                    $priceModifier = $bus->type === 'eksekutif' ? 1.5 : 1.0;
                    $price = $route->base_price * $priceModifier;

                    Schedule::create([
                        'bus_id' => $busId,
                        'route_id' => $route->id,
                        'departure_date' => $date,
                        'departure_time' => $depTime,
                        'arrival_time' => $arrTime,
                        'price' => $price,
                        'available_seats' => $bus->capacity,
                        'status' => 'active',
                    ]);
                }
            }
        }
    }
}
