<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'image' => 'disney.png',
                'title' => 'Disney Adventure - Lion King Celebration',
                'description' => 'Book your sailings now with exclusive offers',
                'promo_code' => 'DISNEY2026',
                'start_date' => '2026-04-25',
                'end_date' => '2026-05-01',
                'link' => '#',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'image' => 'traveloka.png',
                'title' => 'Traveloka Brand Day - Instant Cashback',
                'description' => 'Eksklusif di Traveloka - Instant Cashback s.d. 30%',
                'promo_code' => 'BRANDDAY30',
                'start_date' => '2026-04-25',
                'end_date' => '2026-05-01',
                'link' => '#',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'image' => 'hotel.png',
                'title' => 'Flash Sale Hotel - Diskon hingga 50%',
                'description' => 'Pesan sekarang, menginap kapan saja',
                'promo_code' => 'HOTEL50',
                'start_date' => '2026-04-20',
                'end_date' => '2026-04-30',
                'link' => '#',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'image' => 'flight.png',
                'title' => 'Terbang Hemat - Promo Tiket Pesawat',
                'description' => 'Cashback 20% untuk penerbangan domestik',
                'promo_code' => 'FLY2026',
                'start_date' => '2026-04-15',
                'end_date' => '2026-05-15',
                'link' => '#',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'image' => 'lebaran.png',
                'title' => 'Promo Liburan Lebaran',
                'description' => 'Mudik nyaman dengan promo spesial',
                'promo_code' => 'LEBARAN2026',
                'start_date' => '2026-03-01',
                'end_date' => '2026-04-10',
                'link' => '#',
                'is_active' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($banners as $banner) {
            \App\Models\PromoBanner::create($banner);
        }
    }
}
