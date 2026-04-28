<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Bus 88')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gray-warm-50" x-data="{ sidebarOpen: true }">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-merah-800 to-merah-950 text-white z-40 transition-transform duration-300"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                    <span class="text-merah-600 font-black text-lg">88</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold">Bus 88</h1>
                    <p class="text-xs text-merah-200">Admin Panel</p>
                </div>
            </div>
            <nav class="mt-6 px-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.buses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.buses.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Kelola Bus
                </a>
                <a href="{{ route('admin.routes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.routes.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Kelola Rute
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.schedules.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kelola Jadwal
                </a>
                <a href="{{ route('admin.tour-packages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.tour-packages.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.5m-3 6.065V19a2 2 0 01-2-2v-1a2 2 0 00-2-2 2 2 0 01-2-2v-2.945M18 9.874V5a2 2 0 00-2-2h-1.5a2.5 2.5 0 00-2.5 2.5V5a2 2 0 012 2h1.5a2.5 2.5 0 012.5 2.5z"/></svg>
                    Paket Wisata
                </a>
                <a href="{{ route('admin.flash-sales.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.flash-sales.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Promo Flash Sale
                </a>
                <a href="{{ route('admin.promo-banners.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.promo-banners.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kelola Banner
                </a>


                <div class="pt-4 mt-4 border-t border-white/10">
                    <p class="px-4 text-xs font-semibold text-merah-300 uppercase tracking-wider mb-2">Transaksi</p>
                </div>
                <a href="{{ route('admin.transactions.bookings') }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.transactions.bookings') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Booking Tiket
                    @if($newBookingCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-amber-500 rounded-full shadow-lg shadow-amber-500/30 animate-pulse">{{ $newBookingCount > 99 ? '99+' : $newBookingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.transactions.rentals') }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.transactions.rentals') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Sewa / Charter
                    @if($newRentalCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-amber-500 rounded-full shadow-lg shadow-amber-500/30 animate-pulse">{{ $newRentalCount > 99 ? '99+' : $newRentalCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.transactions.tours') }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.transactions.tours') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.5m-3 6.065V19a2 2 0 01-2-2v-1a2 2 0 00-2-2 2 2 0 01-2-2v-2.945M18 9.874V5a2 2 0 00-2-2h-1.5a2.5 2.5 0 00-2.5 2.5V5a2 2 0 012 2h1.5a2.5 2.5 0 012.5 2.5z"/></svg>
                    Booking Tour
                    @if($newTourCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-amber-500 rounded-full shadow-lg shadow-amber-500/30 animate-pulse">{{ $newTourCount > 99 ? '99+' : $newTourCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.transactions.payments') }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.transactions.payments') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Pembayaran
                    @if($newPaymentCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-amber-500 rounded-full shadow-lg shadow-amber-500/30 animate-pulse">{{ $newPaymentCount > 99 ? '99+' : $newPaymentCount }}</span>
                    @endif
                </a>

                <div class="pt-4 mt-4 border-t border-white/10">
                    <p class="px-4 text-xs font-semibold text-merah-300 uppercase tracking-wider mb-2">Laporan</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-white/15 text-white' : 'text-merah-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan & Cetak PDF
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-merah-100 hover:bg-white/10 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Ke Website
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-merah-100 hover:bg-white/10 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-gray-warm-100">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-warm-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-warm-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <h2 class="text-lg font-bold text-dark">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Notification Bell --}}
                        <div x-data="{ notifOpen: false }" class="relative">
                            @php
                                $adminNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
                                $unreadNotifs = $adminNotifications->where('is_read', false)->count();
                            @endphp
                            <button @click="notifOpen = !notifOpen" class="p-2 relative rounded-full hover:bg-gray-warm-100 transition-colors">
                                <svg class="w-6 h-6 text-gray-warm-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @if($unreadNotifs > 0)
                                <span class="absolute top-1 right-2 w-2.5 h-2.5 bg-merah-600 rounded-full border-2 border-white"></span>
                                @endif
                            </button>

                            {{-- Dropdown --}}
                            <div x-show="notifOpen" @click.away="notifOpen = false" style="display: none;" x-transition
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-warm-100 overflow-hidden z-50">
                                <div class="p-4 border-b border-gray-warm-100 flex items-center justify-between bg-gray-warm-50">
                                    <h3 class="font-bold text-dark">Notifikasi Sistem</h3>
                                    @if($unreadNotifs > 0)
                                    <span class="text-[10px] bg-merah-100 text-merah-600 px-2 py-1 rounded-md font-bold">{{ $unreadNotifs }} Baru</span>
                                    @endif
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    @forelse($adminNotifications as $notif)
                                        <div class="p-4 border-b border-gray-warm-50 {{ $notif->is_read ? 'opacity-60' : 'bg-blue-50/20' }}">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-1">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-dark mb-0.5">{{ $notif->title }}</p>
                                                    <p class="text-xs text-gray-warm-600 mb-1 leading-relaxed">{{ $notif->message }}</p>
                                                    <span class="text-[10px] text-gray-warm-400 font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-8 text-center">
                                            <div class="w-12 h-12 bg-gray-warm-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            </div>
                                            <p class="text-gray-warm-500 text-sm font-medium">Belum ada notifikasi.</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($adminNotifications->count() > 0)
                                <div class="p-3 border-t border-gray-warm-100 text-center bg-gray-warm-50 hover:bg-gray-warm-100 transition-colors">
                                    <form action="{{ route('admin.notifications.read') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-merah-600 w-full hover:underline">Tandai Semua Dibaca</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-px h-8 bg-gray-warm-200 mx-2 hidden md:block"></div>

                        <div class="text-right hidden md:block">
                            <p class="text-sm font-semibold text-dark">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-warm-500">Administrator</p>
                        </div>
                        <div class="w-9 h-9 bg-merah-100 rounded-full flex items-center justify-center">
                            <span class="text-merah-600 font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages with SweetAlert2 --}}
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 3000,
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

            {{-- Confirm Delete Global Script --}}
            <script>
                function confirmDelete(title = 'Hapus data ini?', text = 'Data yang dihapus tidak dapat dikembalikan!') {
                    return Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#cc0000',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1rem'
                    });
                }
            </script>

            {{-- Page Content --}}
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
