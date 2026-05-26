{{-- Splash Screen Preloader --}}
<div x-data="{ showSplash: !sessionStorage.getItem('splashShown'), animateIn: false, animateOut: false }" 
     x-init="
        if (showSplash) {
            sessionStorage.setItem('splashShown', 'true');
            setTimeout(() => animateIn = true, 100);
            setTimeout(() => animateOut = true, 1500);
            setTimeout(() => showSplash = false, 2000);
        }
     "
     x-show="showSplash" 
     x-transition:leave="transition-opacity ease-in-out duration-500"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[100] flex items-center justify-center bg-white/95 backdrop-blur-md">
    
    <div class="flex items-center gap-4 transition-all duration-700 transform"
         :class="{ 'opacity-0 scale-50 translate-y-10': !animateIn, 'opacity-100 scale-100 translate-y-0': animateIn && !animateOut, 'opacity-0 scale-110 -translate-y-10': animateOut }">
        <img src="{{ asset('images/logo.png') }}" alt="Bus 88 Logo" class="h-20 md:h-24 w-auto object-contain drop-shadow-2xl">
        <span class="text-4xl md:text-5xl font-black text-dark tracking-tight drop-shadow-xl">IND'S <span class="text-merah-600">88</span></span>
    </div>
</div>

{{-- Navbar --}}
<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-warm-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Bus 88 Logo" class="h-16 w-auto object-contain transition-transform group-hover:scale-105">
                <span class="text-xl md:text-2xl font-black text-dark tracking-tight">IND'S <span class="text-merah-600">88</span></span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Beranda</a>
                <a href="{{ route('schedules.search') }}?origin=Jakarta&destination=Bandung&date={{ date('Y-m-d') }}" class="text-sm font-medium {{ request()->routeIs('schedules.search') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Jadwal</a>
                <a href="{{ route('rental.index') }}" class="text-sm font-medium {{ request()->routeIs('rental.*') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Sewa Bus</a>
                <a href="{{ route('tour.index') }}" class="text-sm font-medium {{ request()->routeIs('tour.*') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Paket Wisata</a>
                <a href="{{ route('promos.index') }}" class="text-sm font-medium {{ request()->routeIs('promos.*') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Promo</a>
                @auth
                <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard*') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Dashboard</a>
                @endauth
            </div>

            {{-- Auth Buttons --}}
            <div class="hidden md:flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-warm-600 hover:text-merah-600 transition-colors px-4 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm">Daftar</a>
                @else
                    {{-- User Notifications --}}
                    @php
                        $userNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
                        $unreadNotifs = $userNotifications->where('is_read', false)->count();
                    @endphp
                    <div class="relative" id="user-notif-dropdown-wrapper">
                        <button type="button" onclick="document.getElementById('user-notif-dropdown-panel').classList.toggle('hidden'); event.stopPropagation();" class="p-2 relative rounded-full hover:bg-gray-warm-100 transition-colors">
                            <svg class="w-6 h-6 text-gray-warm-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadNotifs > 0)
                            <span class="absolute top-1 right-2 w-2.5 h-2.5 bg-merah-600 rounded-full border-2 border-white"></span>
                            @endif
                        </button>

                        <div id="user-notif-dropdown-panel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-warm-100 overflow-hidden z-50">
                            <div class="p-4 border-b border-gray-warm-100 flex items-center justify-between bg-gray-warm-50">
                                <h3 class="font-bold text-dark">Notifikasi</h3>
                                @if($unreadNotifs > 0)
                                <span class="text-[10px] bg-merah-100 text-merah-600 px-2 py-1 rounded-md font-bold">{{ $unreadNotifs }} Baru</span>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($userNotifications as $notif)
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
                            @if($userNotifications->count() > 0)
                            <div class="p-3 border-t border-gray-warm-100 text-center bg-gray-warm-50 hover:bg-gray-warm-100 transition-colors">
                                <form action="{{ route('notifications.read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-merah-600 w-full hover:underline">Tandai Semua Dibaca</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-px h-8 bg-gray-warm-200 mx-2 hidden md:block"></div>

                    <div class="flex items-center gap-3 relative" id="user-dropdown-wrapper">
                        <button type="button" onclick="document.getElementById('user-dropdown-panel').classList.toggle('hidden'); event.stopPropagation();" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-warm-100 transition-colors">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-merah-500">
    <img 
        src="{{ auth()->user()->avatar 
            ? (Str::startsWith(auth()->user()->avatar, ['http://', 'https://']) ? auth()->user()->avatar : asset('avatar/' . auth()->user()->avatar))
            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=cc0000&color=fff' 
        }}"
        alt="avatar"
        class="w-full h-full object-cover"
    >
</div>
                            <span class="text-sm font-medium text-dark">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="user-dropdown-panel" class="hidden absolute top-14 right-0 w-48 bg-white rounded-xl shadow-xl border border-gray-warm-100 py-2 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-warm-700 hover:bg-gray-warm-50 hover:text-merah-600">Dashboard Saya</a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-warm-700 hover:bg-gray-warm-50 hover:text-merah-600">Admin Panel</a>
                            @endif
                            <hr class="my-1 border-gray-warm-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-merah-600">Keluar</button>
                            </form>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('click', function(event) {
                            var notifWrapper = document.getElementById('user-notif-dropdown-wrapper');
                            var notifPanel = document.getElementById('user-notif-dropdown-panel');
                            if (notifWrapper && notifPanel && !notifWrapper.contains(event.target)) {
                                notifPanel.classList.add('hidden');
                            }

                            var wrapper = document.getElementById('user-dropdown-wrapper');
                            var panel = document.getElementById('user-dropdown-panel');
                            if (wrapper && panel && !wrapper.contains(event.target)) {
                                panel.classList.add('hidden');
                            }
                        });
                    </script>
                @endguest
            </div>

            {{-- Mobile Toggle --}}
            <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg hover:bg-gray-warm-100">
                <svg class="w-6 h-6 text-gray-warm-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-transition class="md:hidden pb-4 border-t border-gray-warm-100 mt-2 pt-4">
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'text-merah-600 bg-merah-50' : 'text-gray-warm-700 hover:bg-gray-warm-100' }} rounded-lg">Beranda</a>
                <a href="{{ route('schedules.search') }}?origin=Jakarta&destination=Bandung&date={{ date('Y-m-d') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('schedules.search') ? 'text-merah-600 bg-merah-50' : 'text-gray-warm-700 hover:bg-gray-warm-100' }} rounded-lg">Jadwal</a>
                <a href="{{ route('rental.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('rental.*') ? 'text-merah-600 bg-merah-50' : 'text-gray-warm-700 hover:bg-gray-warm-100' }} rounded-lg">Sewa Bus</a>
                <a href="{{ route('tour.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('tour.*') ? 'text-merah-600 bg-merah-50' : 'text-gray-warm-700 hover:bg-gray-warm-100' }} rounded-lg">Paket Wisata</a>
                <a href="{{ route('promos.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('promos.*') ? 'text-merah-600 bg-merah-50' : 'text-gray-warm-700 hover:bg-gray-warm-100' }} rounded-lg">Promo</a>

                @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm font-medium text-gray-warm-700 rounded-lg hover:bg-gray-warm-100">Dashboard</a>
                @endauth
                @guest
                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm font-medium text-gray-warm-700 rounded-lg hover:bg-gray-warm-100">Masuk</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm font-medium text-merah-600 rounded-lg hover:bg-merah-50">Daftar</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
