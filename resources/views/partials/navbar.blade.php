{{-- Navbar --}}
<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-warm-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 gradient-merah rounded-xl flex items-center justify-center shadow-lg shadow-merah-600/20 group-hover:shadow-merah-600/40 transition-shadow">
                    <span class="text-white font-black text-lg">88</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-dark">Bus <span class="text-gradient-merah">88</span></h1>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Beranda</a>
                <a href="{{ route('schedules.search') }}?origin=Jakarta&destination=Bandung&date={{ date('Y-m-d') }}" class="text-sm font-medium text-gray-warm-600 hover:text-merah-600 transition-colors">Jadwal</a>
                <a href="{{ route('rental.index') }}" class="text-sm font-medium {{ request()->routeIs('rental.*') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Sewa Bus</a>
                <a href="{{ route('tour.index') }}" class="text-sm font-medium {{ request()->routeIs('tour.*') ? 'text-merah-600' : 'text-gray-warm-600 hover:text-merah-600' }} transition-colors">Paket Wisata</a>
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
                    <div class="flex items-center gap-3" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-warm-100 transition-colors">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-merah-500">
    <img 
        src="{{ auth()->user()->avatar 
            ? asset('avatar/' . auth()->user()->avatar) 
            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=cc0000&color=fff' 
        }}"
        alt="avatar"
        class="w-full h-full object-cover"
    >
</div>
                            <span class="text-sm font-medium text-dark">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="dropdown" @click.away="dropdown = false" x-transition
                             class="absolute top-14 right-4 w-48 bg-white rounded-xl shadow-xl border border-gray-warm-100 py-2">
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
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-medium text-gray-warm-700 rounded-lg hover:bg-gray-warm-100">Beranda</a>
                <a href="{{ route('rental.index') }}" class="block px-4 py-2 text-sm font-medium text-gray-warm-700 rounded-lg hover:bg-gray-warm-100">Sewa Bus</a>
                <a href="{{ route('tour.index') }}" class="block px-4 py-2 text-sm font-medium text-gray-warm-700 rounded-lg hover:bg-gray-warm-100">Paket Wisata</a>
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
