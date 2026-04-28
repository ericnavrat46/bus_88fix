<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PromoBanner::create([
            'title' => 'Liburan Hemat ke Bali',
            'promo_code' => 'BALI30',
            'description' => 'Nikmati diskon s.d. 30% untuk perjalanan ke Bali dengan Bus 88.',
            'image' => 'banner1.png',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        \App\Models\PromoBanner::create([
            'title' => 'Eksplor Jogja',
            'promo_code' => 'JOGJA50',
            'description' => 'Cashback Instant Rp 50rb untuk setiap tiket ke Yogyakarta.',
            'image' => 'banner2.png',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
