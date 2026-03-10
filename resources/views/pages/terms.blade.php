@extends('layouts.app')
@section('title', 'Syarat & Ketentuan - Bus 88')

@section('content')
{{-- Hero --}}
<section class="gradient-merah relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center text-white">
        <h1 class="text-4xl lg:text-5xl font-black mb-4">Syarat & Ketentuan</h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">Terakhir diperbarui: {{ date('d F Y') }}</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            {{-- Section 1 --}}
            <div class="card p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-merah-600 font-bold">1</span>
                    </div>
                    <h2 class="text-xl font-bold text-dark">Ketentuan Umum</h2>
                </div>
                <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                    <p>Dengan mengakses dan menggunakan layanan Bus 88, Anda menyetujui dan terikat dengan syarat dan ketentuan berikut ini. Jika Anda tidak menyetujui ketentuan ini, mohon untuk tidak menggunakan layanan kami.</p>
                    <p>Bus 88 adalah platform penyedia layanan pemesanan tiket bus antar kota dan penyewaan bus (charter) yang beroperasi di wilayah Indonesia.</p>
                    <p>Pengguna wajib berusia minimal 17 tahun atau didampingi oleh orang tua/wali yang sah untuk menggunakan layanan ini.</p>
                </div>
            </div>

            {{-- Section 2 --}}
            <div class="card p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-merah-600 font-bold">2</span>
                    </div>
                    <h2 class="text-xl font-bold text-dark">Pemesanan Tiket</h2>
                </div>
                <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                    <p><strong>a. Proses Pemesanan:</strong> Pemesanan tiket dilakukan melalui website Bus 88 dengan memilih rute, jadwal, dan kursi yang tersedia. Setelah mengisi data penumpang, pengguna akan diarahkan ke halaman pembayaran.</p>
                    <p><strong>b. Pembayaran:</strong> Pembayaran dilakukan melalui Midtrans Payment Gateway. Metode pembayaran yang tersedia meliputi transfer bank, e-wallet, kartu kredit/debit, dan metode lainnya yang didukung oleh Midtrans.</p>
                    <p><strong>c. Konfirmasi:</strong> Tiket dianggap sah setelah pembayaran berhasil diverifikasi. E-tiket akan tersedia di dashboard pengguna.</p>
                    <p><strong>d. Batas Waktu Pembayaran:</strong> Pembayaran harus diselesaikan dalam waktu 2 jam setelah pemesanan dibuat. Pemesanan yang tidak dibayar dalam batas waktu akan otomatis dibatalkan.</p>
                    <p><strong>e. Data Penumpang:</strong> Pengguna bertanggung jawab atas kebenaran data penumpang yang diisi. Bus 88 tidak bertanggung jawab atas kesalahan informasi yang diberikan oleh pengguna.</p>
                </div>
            </div>

            {{-- Section 3 --}}
            <div class="card p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-merah-600 font-bold">3</span>
                    </div>
                    <h2 class="text-xl font-bold text-dark">Penyewaan Bus (Charter)</h2>
                </div>
                <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                    <p><strong>a. Pengajuan:</strong> Penyewaan bus diajukan melalui formulir pada halaman Charter. Pengajuan akan ditinjau oleh tim admin kami.</p>
                    <p><strong>b. Persetujuan:</strong> Admin akan meninjau pengajuan dan menentukan harga serta ketersediaan bus. Pengguna akan diberitahu melalui dashboard mengenai status persetujuan.</p>
                    <p><strong>c. Pembayaran:</strong> Setelah pengajuan disetujui, pengguna wajib melakukan pembayaran sesuai harga yang telah ditentukan.</p>
                    <p><strong>d. Pembatalan:</strong> Pembatalan sewa bus yang sudah disetujui dapat dikenakan biaya pembatalan. Hubungi layanan pelanggan kami untuk informasi lebih lanjut.</p>
                </div>
            </div>

            {{-- Section 4 --}}
            <div class="card p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-merah-600 font-bold">4</span>
                    </div>
                    <h2 class="text-xl font-bold text-dark">Pembatalan & Pengembalian Dana</h2>
                </div>
                <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                    <p><strong>a.</strong> Pembatalan tiket yang dilakukan minimal 24 jam sebelum keberangkatan akan mendapat pengembalian dana sebesar 75% dari harga tiket.</p>
                    <p><strong>b.</strong> Pembatalan tiket yang dilakukan kurang dari 24 jam sebelum keberangkatan akan mendapat pengembalian dana sebesar 50% dari harga tiket.</p>
                    <p><strong>c.</strong> Tiket yang tidak digunakan tanpa pemberitahuan (no-show) tidak mendapat pengembalian dana.</p>
                    <p><strong>d.</strong> Proses pengembalian dana membutuhkan waktu 7–14 hari kerja tergantung metode pembayaran.</p>
                </div>
            </div>

            {{-- Section 5 --}}
            <div class="card p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-merah-600 font-bold">5</span>
                    </div>
                    <h2 class="text-xl font-bold text-dark">Tanggung Jawab Penumpang</h2>
                </div>
                <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                    <p><strong>a.</strong> Penumpang wajib hadir di lokasi keberangkatan minimal 30 menit sebelum jadwal yang tertera.</p>
                    <p><strong>b.</strong> Penumpang wajib membawa dokumen identitas yang sah (KTP/SIM/Paspor).</p>
                    <p><strong>c.</strong> Penumpang dilarang membawa barang-barang berbahaya, terlarang, atau yang melanggar hukum.</p>
                    <p><strong>d.</strong> Penumpang wajib menjaga ketertiban dan kenyamanan selama perjalanan.</p>
                    <p><strong>e.</strong> Bus 88 tidak bertanggung jawab atas kehilangan atau kerusakan barang bawaan penumpang.</p>
                </div>
            </div>

            {{-- Section 6 --}}
            <div class="card p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-merah-600 font-bold">6</span>
                    </div>
                    <h2 class="text-xl font-bold text-dark">Perubahan Ketentuan</h2>
                </div>
                <div class="text-gray-warm-600 leading-relaxed space-y-3 text-sm">
                    <p>Bus 88 berhak untuk mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Perubahan akan berlaku efektif sejak dipublikasikan di website. Pengguna disarankan untuk membaca halaman ini secara berkala.</p>
                </div>
            </div>

            {{-- Contact --}}
            <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100 text-center">
                <p class="text-sm text-gray-warm-600 mb-2">Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi:</p>
                <p class="font-semibold text-merah-600">info@bus88.com · (021) 8888-8888</p>
            </div>
        </div>
    </div>
</section>
@endsection
