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

    {{-- Global Print Loading Overlay (Redesign Premium) --}}
    <div id="print-loading-overlay" class="fixed inset-0 z-[9999] bg-white hidden transition-opacity duration-300 opacity-0" style="display:none;">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
            {{-- Animated Ticket Icon --}}
            <div class="relative mb-8">
                <div class="w-28 h-28 bg-merah-50 rounded-full flex items-center justify-center ticket-icon-pulse">
                    <div class="w-20 h-20 bg-merah-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-merah-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                    </div>
                </div>
                {{-- Sparkles --}}
                <div class="absolute top-2 right-2 sparkle-float" style="animation-delay: 0s;">
                    <svg class="w-4 h-4 text-merah-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.09 6.26L20.18 10l-6.09 1.74L12 18l-2.09-6.26L3.82 10l6.09-1.74L12 2z"/></svg>
                </div>
                <div class="absolute top-6 -left-1 sparkle-float" style="animation-delay: 0.4s;">
                    <svg class="w-3 h-3 text-merah-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.09 6.26L20.18 10l-6.09 1.74L12 18l-2.09-6.26L3.82 10l6.09-1.74L12 2z"/></svg>
                </div>
                <div class="absolute -bottom-1 right-4 sparkle-float" style="animation-delay: 0.8s;">
                    <svg class="w-3.5 h-3.5 text-merah-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.09 6.26L20.18 10l-6.09 1.74L12 18l-2.09-6.26L3.82 10l6.09-1.74L12 2z"/></svg>
                </div>
            </div>

            {{-- Title --}}
            <h2 class="text-xl md:text-2xl font-black text-dark mb-2 text-center">Sedang menyiapkan e-tiket Anda</h2>
            <p class="text-sm text-gray-warm-500 mb-8 text-center">Proses ini hanya memerlukan beberapa detik.</p>

            {{-- Progress Bar --}}
            <div class="w-full max-w-md mb-8 px-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-warm-400">Memproses...</span>
                    <span id="print-progress-pct" class="text-sm font-black text-merah-600">0%</span>
                </div>
                <div class="w-full h-2.5 bg-gray-warm-100 rounded-full overflow-hidden">
                    <div id="print-progress-bar" class="h-full bg-gradient-to-r from-merah-600 to-merah-500 rounded-full transition-all duration-300 ease-out" style="width: 0%;"></div>
                </div>
            </div>

            {{-- Step Indicators --}}
            <div class="w-full max-w-lg mb-10 px-4">
                <div class="flex items-start justify-between relative">
                    {{-- Connecting line --}}
                    <div class="absolute top-4 left-[10%] right-[10%] h-0.5 bg-gray-warm-100 z-0"></div>
                    <div id="print-step-line" class="absolute top-4 left-[10%] h-0.5 bg-merah-600 z-[1] transition-all duration-500 ease-out" style="width: 0%;"></div>

                    <div class="flex flex-col items-center relative z-10 w-1/5" id="print-step-1">
                        <div class="w-8 h-8 rounded-full bg-gray-warm-100 text-gray-warm-400 flex items-center justify-center text-xs font-bold mb-2 transition-all duration-300 step-circle">1</div>
                        <span class="text-[10px] md:text-xs text-gray-warm-400 text-center font-medium leading-tight transition-colors duration-300 step-label">Memverifikasi data</span>
                    </div>
                    <div class="flex flex-col items-center relative z-10 w-1/5" id="print-step-2">
                        <div class="w-8 h-8 rounded-full bg-gray-warm-100 text-gray-warm-400 flex items-center justify-center text-xs font-bold mb-2 transition-all duration-300 step-circle">2</div>
                        <span class="text-[10px] md:text-xs text-gray-warm-400 text-center font-medium leading-tight transition-colors duration-300 step-label">Memproses pemesanan</span>
                    </div>
                    <div class="flex flex-col items-center relative z-10 w-1/5" id="print-step-3">
                        <div class="w-8 h-8 rounded-full bg-gray-warm-100 text-gray-warm-400 flex items-center justify-center text-xs font-bold mb-2 transition-all duration-300 step-circle">3</div>
                        <span class="text-[10px] md:text-xs text-gray-warm-400 text-center font-medium leading-tight transition-colors duration-300 step-label">Mencetak e-tiket</span>
                    </div>
                    <div class="flex flex-col items-center relative z-10 w-1/5" id="print-step-4">
                        <div class="w-8 h-8 rounded-full bg-gray-warm-100 text-gray-warm-400 flex items-center justify-center text-xs font-bold mb-2 transition-all duration-300 step-circle">4</div>
                        <span class="text-[10px] md:text-xs text-gray-warm-400 text-center font-medium leading-tight transition-colors duration-300 step-label">Menyiapkan dokumen</span>
                    </div>
                    <div class="flex flex-col items-center relative z-10 w-1/5" id="print-step-5">
                        <div class="w-8 h-8 rounded-full bg-gray-warm-100 text-gray-warm-400 flex items-center justify-center text-xs font-bold mb-2 transition-all duration-300 step-circle">5</div>
                        <span class="text-[10px] md:text-xs text-gray-warm-400 text-center font-medium leading-tight transition-colors duration-300 step-label">Hampir selesai</span>
                    </div>
                </div>
            </div>

            {{-- Security Badge --}}
            <div class="w-full max-w-md px-4 mb-6">
                <div class="flex items-center gap-3 p-4 bg-merah-50 rounded-2xl border border-merah-100">
                    <div class="w-9 h-9 bg-merah-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-dark">Data Anda aman</p>
                        <p class="text-xs text-gray-warm-500">Kami memastikan data dan pembayaran Anda terlindungi dengan aman.</p>
                    </div>
                </div>
            </div>

            {{-- Cancel Button --}}
            <button id="print-cancel-btn" class="text-sm font-bold text-merah-600 hover:text-merah-700 flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Batalkan
            </button>
        </div>
    </div>

    <style>
        @keyframes ticket-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .ticket-icon-pulse { animation: ticket-pulse 2s ease-in-out infinite; }

        @keyframes sparkle-float {
            0%, 100% { opacity: 0.3; transform: translateY(0) scale(0.8); }
            50% { opacity: 1; transform: translateY(-6px) scale(1.2); }
        }
        .sparkle-float { animation: sparkle-float 1.8s ease-in-out infinite; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('print-loading-overlay');
            const progressBar = document.getElementById('print-progress-bar');
            const progressPct = document.getElementById('print-progress-pct');
            const stepLine = document.getElementById('print-step-line');
            const cancelBtn = document.getElementById('print-cancel-btn');
            let printTimer = null;
            let stepTimers = [];

            const stepConfig = [
                { pct: 15, lineW: '0%',  delay: 0 },
                { pct: 35, lineW: '20%', delay: 600 },
                { pct: 58, lineW: '40%', delay: 1400 },
                { pct: 78, lineW: '60%', delay: 2200 },
                { pct: 100, lineW: '80%', delay: 3000 },
            ];

            function activateStep(stepNum, state) {
                const el = document.getElementById('print-step-' + stepNum);
                if (!el) return;
                const circle = el.querySelector('.step-circle');
                const label = el.querySelector('.step-label');

                if (state === 'done') {
                    circle.classList.remove('bg-gray-warm-100', 'text-gray-warm-400', 'bg-merah-600', 'text-white', 'ring-4', 'ring-merah-100');
                    circle.classList.add('bg-merah-600', 'text-white');
                    circle.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                    label.classList.remove('text-gray-warm-400', 'text-merah-600', 'font-bold');
                    label.classList.add('text-dark');
                } else if (state === 'active') {
                    circle.classList.remove('bg-gray-warm-100', 'text-gray-warm-400');
                    circle.classList.add('bg-merah-600', 'text-white', 'ring-4', 'ring-merah-100');
                    label.classList.remove('text-gray-warm-400');
                    label.classList.add('text-merah-600', 'font-bold');
                }
            }

            function resetOverlay() {
                progressBar.style.width = '0%';
                progressPct.textContent = '0%';
                stepLine.style.width = '0%';
                for (let i = 1; i <= 5; i++) {
                    const el = document.getElementById('print-step-' + i);
                    if (!el) continue;
                    const circle = el.querySelector('.step-circle');
                    const label = el.querySelector('.step-label');
                    circle.className = 'w-8 h-8 rounded-full bg-gray-warm-100 text-gray-warm-400 flex items-center justify-center text-xs font-bold mb-2 transition-all duration-300 step-circle';
                    circle.textContent = i;
                    label.className = 'text-[10px] md:text-xs text-gray-warm-400 text-center font-medium leading-tight transition-colors duration-300 step-label';
                }
            }

            function showOverlay() {
                resetOverlay();
                overlay.style.display = '';
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                }, 10);

                // Animate steps
                stepConfig.forEach((cfg, idx) => {
                    const t = setTimeout(() => {
                        // Mark previous as done
                        if (idx > 0) activateStep(idx, 'done');
                        // Mark current as active
                        activateStep(idx + 1, 'active');
                        // Update progress
                        progressBar.style.width = cfg.pct + '%';
                        progressPct.textContent = cfg.pct + '%';
                        stepLine.style.width = cfg.lineW;
                    }, cfg.delay);
                    stepTimers.push(t);
                });

                // Mark last step done & hide
                printTimer = setTimeout(() => {
                    activateStep(5, 'done');
                    stepLine.style.width = '80%';
                    progressBar.style.width = '100%';
                    progressPct.textContent = '100%';

                    setTimeout(() => hideOverlay(), 600);
                }, 3800);
            }

            function hideOverlay() {
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    overlay.style.display = 'none';
                }, 300);
                clearTimeout(printTimer);
                stepTimers.forEach(t => clearTimeout(t));
                stepTimers = [];
            }

            // Cancel button
            if (cancelBtn) {
                cancelBtn.addEventListener('click', hideOverlay);
            }

            // Intercept ticket download links
            const printLinks = document.querySelectorAll('a[href*="/download"]');
            printLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (this.href.includes('/ticket/')) {
                        if (this.getAttribute('target') === '_blank') {
                            this.removeAttribute('target');
                        }
                        showOverlay();
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
