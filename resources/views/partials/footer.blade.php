{{-- Footer --}}
<footer class="bg-dark text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-16 grid grid-cols-1 md:grid-cols-4 gap-10">
            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-4 text-white">
                    <h1 class="text-xl font-black italic tracking-tighter uppercase">IND'S 88 TRANS</h1>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Layanan tiket bus antar kota, pariwisata, dan sewa bus terpercaya di Indonesia. Perjalanan aman, nyaman, dan tepat waktu.
                </p>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="font-semibold text-sm uppercase tracking-wider text-gray-warm-400 mb-4">Layanan</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-sm text-gray-warm-300 hover:text-merah-400 transition-colors">Beli Tiket</a></li>
                    <li><a href="{{ route('rental.index') }}" class="text-sm text-gray-warm-300 hover:text-merah-400 transition-colors">Sewa Bus</a></li>
                    <li><a href="#" class="text-sm text-gray-warm-300 hover:text-merah-400 transition-colors">Rute Populer</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-sm uppercase tracking-wider text-gray-warm-400 mb-4">Perusahaan</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-sm text-gray-warm-300 hover:text-merah-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('terms') }}" class="text-sm text-gray-warm-300 hover:text-merah-400 transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-sm text-gray-warm-300 hover:text-merah-400 transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-sm uppercase tracking-wider text-gray-warm-400 mb-4">Hubungi Kami</h4>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-sm text-gray-warm-300">
                        <svg class="w-4 h-4 text-merah-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        (021) 8888-8888
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-warm-300">
                        <svg class="w-4 h-4 text-merah-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        info@bus88.com
                    </li>
                </ul>
            </div>
        </div>

        <div class="py-6 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-warm-500">&copy; {{ date('Y') }} Bus 88. Semua hak dilindungi.</p>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-warm-500">Pembayaran aman via</span>
                <div class="px-3 py-1 bg-white/10 rounded-lg">
                    <span class="text-sm font-semibold text-white">Midtrans</span>
                </div>
            </div>
        </div>
    </div>
</footer>
