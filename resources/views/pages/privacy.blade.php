@extends('layouts.app')
@section('title', 'Kebijakan Privasi - Bus 88')

@section('content')
{{-- Hero --}}
<section class="gradient-merah relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center text-white">
        <h1 class="text-4xl lg:text-5xl font-black mb-4">Kebijakan Privasi</h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">Terakhir diperbarui: {{ date('d F Y') }}</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Intro --}}
        <div class="card p-8 mb-6">
            <p class="text-gray-warm-600 leading-relaxed text-sm">
                Bus 88 berkomitmen untuk melindungi dan menghormati privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami. Dengan menggunakan website dan layanan Bus 88, Anda menyetujui pengumpulan dan penggunaan informasi sesuai kebijakan ini.
            </p>
        </div>

        {{-- Section 1 --}}
        <div class="card p-8 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-dark">Informasi yang Kami Kumpulkan</h2>
            </div>
            <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                <p>Kami mengumpulkan informasi berikut saat Anda menggunakan layanan Bus 88:</p>
                <ul class="space-y-2 ml-4">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Data Pribadi:</strong> Nama lengkap, alamat email, nomor telepon, dan alamat (saat pendaftaran akun)</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Data Penumpang:</strong> Nama, nomor identitas (KTP/SIM), dan nomor telepon penumpang (saat pemesanan tiket)</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Data Transaksi:</strong> Riwayat pemesanan, detail pembayaran, dan status transaksi</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Data Teknis:</strong> Alamat IP, jenis browser, sistem operasi, dan data cookies</li>
                </ul>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="card p-8 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-dark">Penggunaan Informasi</h2>
            </div>
            <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                <p>Informasi yang kami kumpulkan digunakan untuk:</p>
                <ul class="space-y-2 ml-4">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Memproses pemesanan tiket dan penyewaan bus</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Mengirimkan konfirmasi dan e-tiket</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Memproses pembayaran melalui Midtrans Payment Gateway</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Menghubungi Anda terkait pemesanan atau layanan pelanggan</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Meningkatkan kualitas layanan dan pengalaman pengguna</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Memenuhi kewajiban hukum yang berlaku</li>
                </ul>
            </div>
        </div>

        {{-- Section 3 --}}
        <div class="card p-8 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-dark">Keamanan Data</h2>
            </div>
            <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                <p>Kami mengambil langkah-langkah keamanan yang wajar untuk melindungi informasi pribadi Anda:</p>
                <ul class="space-y-2 ml-4">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Enkripsi:</strong> Seluruh data sensitif dienkripsi saat penyimpanan dan transmisi menggunakan protokol SSL/TLS</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Pembayaran Aman:</strong> Data pembayaran diproses langsung oleh Midtrans dan tidak disimpan di server kami</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Akses Terbatas:</strong> Hanya personel yang berwenang yang memiliki akses ke data pribadi pengguna</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Password:</strong> Password pengguna disimpan dalam bentuk hash dan tidak dapat dibaca</li>
                </ul>
            </div>
        </div>

        {{-- Section 4 --}}
        <div class="card p-8 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-dark">Berbagi Informasi dengan Pihak Ketiga</h2>
            </div>
            <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                <p>Kami <strong>tidak menjual</strong> informasi pribadi Anda kepada pihak ketiga. Namun, kami dapat membagikan data Anda kepada:</p>
                <ul class="space-y-2 ml-4">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Midtrans:</strong> Sebagai payment gateway untuk memproses pembayaran Anda</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Otoritas Hukum:</strong> Jika diwajibkan oleh peraturan perundang-undangan yang berlaku</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Penyedia Layanan:</strong> Pihak ketiga terpercaya yang membantu operasional kami (hosting, email) dengan perjanjian kerahasiaan</li>
                </ul>
            </div>
        </div>

        {{-- Section 5 --}}
        <div class="card p-8 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-dark">Hak Pengguna</h2>
            </div>
            <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                <p>Anda memiliki hak untuk:</p>
                <ul class="space-y-2 ml-4">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Mengakses:</strong> Melihat data pribadi yang kami simpan tentang Anda</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Memperbaiki:</strong> Memperbarui informasi yang tidak akurat melalui halaman profil</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Menghapus:</strong> Meminta penghapusan akun dan data pribadi Anda</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> <strong>Menolak:</strong> Menolak penggunaan data Anda untuk tujuan pemasaran</li>
                </ul>
                <p>Untuk menggunakan hak-hak tersebut, silakan hubungi kami di <strong>info@bus88.com</strong>.</p>
            </div>
        </div>

        {{-- Section 6 --}}
        <div class="card p-8 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <h2 class="text-xl font-bold text-dark">Cookies</h2>
            </div>
            <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                <p>Website kami menggunakan cookies untuk:</p>
                <ul class="space-y-2 ml-4">
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Menjaga sesi login Anda tetap aktif</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Mengingat preferensi Anda</li>
                    <li class="flex items-start gap-2"><span class="text-merah-600 mt-1">•</span> Menganalisis penggunaan website untuk peningkatan layanan</li>
                </ul>
                <p>Anda dapat mengatur pengaturan cookies di browser Anda. Menonaktifkan cookies dapat memengaruhi beberapa fungsi website.</p>
            </div>
        </div>

        {{-- Contact --}}
        <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100 text-center">
            <p class="text-sm text-gray-warm-600 mb-2">Pertanyaan mengenai kebijakan privasi ini? Hubungi kami:</p>
            <p class="font-semibold text-merah-600">info@bus88.com · (021) 8888-8888</p>
        </div>
    </div>
</section>
@endsection
