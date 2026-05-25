<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Bus 88 - Layanan tiket bus antar kota dan sewa bus terpercaya di Indonesia">
    <title>@yield('title', 'Bus 88 - Tiket Bus & Sewa Bus Terpercaya')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col" x-data="{ mobileMenu: false }">
    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Flash Messages with SweetAlert2 --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
                borderRadius: '1rem'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                borderRadius: '1rem'
            });
        });
    </script>
    @endif

    @if(isset($errors) && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Validasi',
                html: '<ul class="text-left text-sm list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                borderRadius: '1rem'
            });
        });
    </script>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    <script>
        function confirmCancel(event, formElement, title = 'Apakah Anda yakin?', text = 'Pesanan ini akan dibatalkan!') {
            event.preventDefault();
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                borderRadius: '1rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
        }
    </script>

    {{-- Global Print Loading Overlay --}}
    <div id="print-loading-overlay" class="fixed inset-0 z-[9999] bg-white/90 backdrop-blur-sm hidden flex-col items-center justify-center transition-opacity duration-300 opacity-0">
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Sedang Mencetak E-Tiket...</h2>
        <p class="text-slate-500 mb-10 text-sm">Mohon tunggu sebentar, tiket Anda sedang disiapkan.</p>
        
        <div class="relative w-64 h-16 mx-auto overflow-hidden">
            <!-- Road -->
            <div class="absolute bottom-0 w-full h-1 bg-slate-300 rounded"></div>
            <!-- Road lines -->
            <div class="absolute bottom-1 w-full flex justify-between px-2 opacity-50">
                <div class="w-4 h-1 bg-slate-400"></div>
                <div class="w-4 h-1 bg-slate-400"></div>
                <div class="w-4 h-1 bg-slate-400"></div>
                <div class="w-4 h-1 bg-slate-400"></div>
            </div>
            <!-- Moving Bus -->
            <div class="absolute bottom-0 text-red-600" style="animation: drive 2.5s ease-in-out infinite;">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z" />
                </svg>
            </div>
        </div>
    </div>

    <style>
        @keyframes drive {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(250%); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap semua link download tiket
            const printLinks = document.querySelectorAll('a[href*="/download"]');
            
            printLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if(this.href.includes('/ticket/')) {
                        // Hapus target=_blank jika ada agar browser tidak buka tab kosong
                        if (this.getAttribute('target') === '_blank') {
                            this.removeAttribute('target');
                        }
                        
                        const overlay = document.getElementById('print-loading-overlay');
                        overlay.classList.remove('hidden');
                        
                        setTimeout(() => {
                            overlay.classList.remove('opacity-0');
                            overlay.classList.add('opacity-100');
                        }, 10);
                        
                        // Sembunyikan otomatis setelah 4 detik (asumsi PDF siap diunduh)
                        setTimeout(() => {
                            overlay.classList.remove('opacity-100');
                            overlay.classList.add('opacity-0');
                            
                            setTimeout(() => {
                                overlay.classList.add('hidden');
                            }, 300);
                        }, 4000);
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
