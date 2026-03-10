@extends('layouts.app')
@section('title', 'Tentang Kami - Bus 88')

@section('content')
{{-- Hero --}}
<section class="gradient-merah relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center text-white">
        <h1 class="text-4xl lg:text-5xl font-black mb-4">Tentang <span class="underline decoration-4 decoration-white/30 underline-offset-4">Bus 88</span></h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">Menghubungkan kota-kota di Indonesia dengan layanan transportasi bus yang aman, nyaman, dan terpercaya sejak 2012.</p>
    </div>
</section>

{{-- Story --}}
<section class="py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-merah-100 rounded-full mb-6">
                    <span class="w-2 h-2 bg-merah-600 rounded-full"></span>
                    <span class="text-sm font-semibold text-merah-600">Cerita Kami</span>
                </div>
                <h2 class="text-3xl font-black text-dark mb-6">Perjalanan yang Dimulai dari <span class="text-gradient-merah">Semangat Melayani</span></h2>
                <p class="text-gray-warm-600 leading-relaxed mb-4">
                    Bus 88 didirikan pada tahun 2012 dengan visi sederhana: memberikan layanan transportasi bus yang aman, nyaman, dan terjangkau bagi seluruh masyarakat Indonesia. Berawal dari 3 armada kecil yang melayani rute Jakarta–Bandung, kini kami telah berkembang menjadi salah satu penyedia jasa transportasi bus terkemuka.
                </p>
                <p class="text-gray-warm-600 leading-relaxed mb-4">
                    Nama "88" melambangkan keberuntungan dan kemakmuran — sebuah harapan kami bahwa setiap perjalanan bersama Bus 88 membawa kebaikan bagi penumpang kami. Dengan semangat Merah Putih, kami bangga menjadi bagian dari solusi transportasi nasional.
                </p>
                <p class="text-gray-warm-600 leading-relaxed">
                    Saat ini, Bus 88 mengoperasikan lebih dari 50 armada dengan berbagai kelas — dari Ekonomi hingga Eksekutif — yang melayani puluhan rute antar kota di Pulau Jawa dan sekitarnya.
                </p>
            </div>
            <div class="space-y-6">
                <div class="card-premium p-6 flex items-start gap-4">
                    <div class="w-14 h-14 bg-merah-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-1">Keselamatan Utama</h3>
                        <p class="text-sm text-gray-warm-500">Seluruh armada melewati inspeksi rutin dan pengemudi kami bersertifikat profesional.</p>
                    </div>
                </div>
                <div class="card-premium p-6 flex items-start gap-4">
                    <div class="w-14 h-14 bg-merah-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-1">Harga Transparan</h3>
                        <p class="text-sm text-gray-warm-500">Tidak ada biaya tersembunyi. Harga yang Anda lihat adalah harga yang Anda bayar.</p>
                    </div>
                </div>
                <div class="card-premium p-6 flex items-start gap-4">
                    <div class="w-14 h-14 bg-merah-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-1">Inovasi Digital</h3>
                        <p class="text-sm text-gray-warm-500">Booking online, pembayaran digital via Midtrans, dan e-tiket instan untuk kemudahan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-20 bg-cream">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-black text-dark mb-4">Bus 88 dalam <span class="text-gradient-merah">Angka</span></h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card-premium p-8 text-center">
                <p class="text-4xl font-black text-merah-600 mb-2">15+</p>
                <p class="text-sm text-gray-warm-500 font-medium">Tahun Beroperasi</p>
            </div>
            <div class="card-premium p-8 text-center">
                <p class="text-4xl font-black text-merah-600 mb-2">50+</p>
                <p class="text-sm text-gray-warm-500 font-medium">Armada Bus</p>
            </div>
            <div class="card-premium p-8 text-center">
                <p class="text-4xl font-black text-merah-600 mb-2">30+</p>
                <p class="text-sm text-gray-warm-500 font-medium">Rute Aktif</p>
            </div>
            <div class="card-premium p-8 text-center">
                <p class="text-4xl font-black text-merah-600 mb-2">1JT+</p>
                <p class="text-sm text-gray-warm-500 font-medium">Penumpang Dilayani</p>
            </div>
        </div>
    </div>
</section>

{{-- Vision & Mission --}}
<section class="py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-10">
            <div class="card p-8 border-t-4 border-t-merah-600">
                <div class="w-12 h-12 bg-merah-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-4">Visi Kami</h3>
                <p class="text-gray-warm-600 leading-relaxed">Menjadi perusahaan transportasi bus terdepan di Indonesia yang mengutamakan keselamatan, kenyamanan, dan inovasi teknologi dalam melayani masyarakat.</p>
            </div>
            <div class="card p-8 border-t-4 border-t-merah-600">
                <div class="w-12 h-12 bg-merah-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-4">Misi Kami</h3>
                <ul class="text-gray-warm-600 leading-relaxed space-y-2">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Menyediakan armada berkualitas tinggi dengan perawatan rutin</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Memberikan pelayanan prima dengan staf profesional</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Menghadirkan sistem pemesanan digital yang mudah diakses</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Menawarkan harga yang kompetitif dan transparan</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Berkontribusi pada perkembangan transportasi nasional</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Contact CTA --}}
<section class="py-20 gradient-merah-dark relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 30px 30px;"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-3xl lg:text-4xl font-black mb-6">Hubungi Kami</h2>
        <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">Ada pertanyaan atau ingin bekerja sama? Tim kami siap membantu Anda.</p>
        <div class="grid sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <p class="font-semibold">Telepon</p>
                <p class="text-sm text-white/70">(021) 8888-8888</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-semibold">Email</p>
                <p class="text-sm text-white/70">info@bus88.com</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="font-semibold">Kantor Pusat</p>
                <p class="text-sm text-white/70">Jakarta Selatan</p>
            </div>
        </div>
    </div>
</section>
@endsection
