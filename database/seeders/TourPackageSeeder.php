<?php

namespace Database\Seeders;

use App\Models\TourPackage;
use Illuminate\Database\Seeder;

class TourPackageSeeder extends Seeder
{
    public function run(): void
    {
        TourPackage::create([
            'name' => 'Pesona Bali & Nusa Penida',
            'slug' => 'pesona-bali-nusa-penida',
            'description' => "Nikmati keindahan pulau dewata dengan rute wisata terlengkap. Mulai dari pantai eksotis, pura yang megah, hingga petualangan di Nusa Penida.\n\nHari 1: Penjemputan di Bandara + Pantai Pandawa\nHari 2: Nusa Penida Full Day (Kelingking Beach, Broken Beach, Angel Billabong)\nHari 3: Kintamani & Ubud Tour\nHari 4: Tanah Lot & Oleh-oleh Krisna",
            'duration_days' => 4,
            'price_per_person' => 3500000,
            'destinations' => ['Pantai Pandawa', 'Nusa Penida', 'Kintamani', 'Ubud', 'Tanah Lot'],
            'inclusions' => [
                'Transportasi Bus Pariwisata AC',
                'Hotel Bintang 4 (3 Malam)',
                'Makan Sesuai Program',
                'Tiket Masuk Wisata',
                'Fast Boat Nusa Penida PP',
                'Tour Guide Berpengalaman'
            ],
            'exclusions' => [
                'Tiket Pesawat PP',
                'Pengeluaran Pribadi',
                'Tips Guide/Driver (Sukarela)'
            ],
            'status' => 'active',
        ]);

        TourPackage::create([
            'name' => 'Eksplor Yogyakarta Cultural Heritage',
            'slug' => 'eksplor-yogyakarta-heritage',
            'description' => "Jelajahi kekayaan budaya dan sejarah di Yogyakarta. Mengunjungi candi-candi megah dan menikmati suasana Malioboro yang khas.",
            'duration_days' => 3,
            'price_per_person' => 1850000,
            'destinations' => ['Candi Borobudur', 'Candi Prambanan', 'Keraton Yogyakarta', 'Malioboro', 'Gua Pindul'],
            'inclusions' => [
                'Bus Pariwisata Medium',
                'Hotel Bintang 3 (2 Malam)',
                'Sarapan & Makan Siang',
                'Peralatan Tubing Gua Pindul',
                'Air Mineral Selama Perjalanan'
            ],
            'exclusions' => [
                'Tiket Kereta/Pesawat',
                'Makan Malam',
                'Belanja Pribadi'
            ],
            'status' => 'active',
        ]);
    }
}
