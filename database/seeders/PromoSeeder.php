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
        \App\Models\Promo::create([
            'title' => 'Liburan Hemat ke Bali',
            'slug' => 'liburan-hemat-ke-bali',
            'description' => 'Nikmati diskon s.d. 30% untuk perjalanan ke Bali dengan Bus 88.',
            'image' => 'promos/banner1.png',
            'is_active' => true,
        ]);

        \App\Models\Promo::create([
            'title' => 'Eksplor Jogja',
            'slug' => 'eksplor-jogja',
            'description' => 'Cashback Instant Rp 50rb untuk setiap tiket ke Yogyakarta.',
            'image' => 'promos/banner2.png',
            'is_active' => true,
        ]);
    }
}
