<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- vite --}}
    @vite('resources/css/app.css')
    <title>{{ config('app.name') }} | @yield('title', 'SiData Surakarta - Data Terbuka Kota Surakarta')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/SidataLogoIcon.png') }}">
</head>
<body>
    <nav 
    x-data="{ scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
    :class="scrolled ? 'bg-white shadow-md backdrop-blur-md bg-opacity-90' : 'bg-transparent'"
    class="fixed top-0 left-0 w-full px-24 py-4 flex items-center justify-between transition-all duration-300 z-50"
>
    <div class="w-32">
        {{-- Logo --}}
        <img src="{{ asset('images/SidataLogo.png') }}" alt="">
    </div>

    <ul class="flex space-x-6 text-gray-800 font-medium">
        {{-- Link Beranda dinamis berdasarkan login & role --}}
        <li>
            @auth
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                    Beranda
                </a>
            @else
                <a href="{{ url('/') }}"
                   class="{{ request()->is('/') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                    Beranda
                </a>
            @endauth
        </li>

        @guest
            {{-- Menu untuk pengguna yang belum login --}}
            <li>
                <a href="/tentang"
                   class="{{ request()->is('tentang') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                    Tentang
                </a>
            </li>
        @endguest

        @auth
            {{-- Menu khusus berdasarkan role --}}
            @if (Auth::user()->role == 'opd')
                <li>
                    <a href="{{ route('data.form') }}"
                       class="{{ request()->routeIs('data.form') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                        Unggah Data
                    </a>
                </li>
            @elseif (Auth::user()->role == 'kelurahan')
                <li>
                    <a href="/data-kelurahan"
                       class="{{ request()->is('data-kelurahan') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                        Data Kelurahan
                    </a>
                </li>
            @elseif (Auth::user()->role == 'admin')
                <li>
                    <a href="{{route('users.index')}}"
                       class="{{ request()->is('users.index') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                        Kelola Pengguna
                    </a>
                </li>
                <li>
                    <a href="/data-kategori"
                       class="{{ request()->is('data-kategori') ? 'text-black pb-1 border-b-2 border-[#FE482B]' : 'text-black' }} transition">
                        Kategori
                    </a>
                </li>
            @endif
        @endauth
    </ul>

    @guest
        <a href="/login" class="bg-[#FE482B] hover:bg-orange-600 text-white font-semibold px-5 py-1 rounded-xl transition">
            Login
        </a>
    @endguest

    @auth
        <div class="flex items-center space-x-2 relative" x-data="{ open: false }">
            <span class="text-gray-800 font-medium">Halo, {{ Auth::user()->name }}</span>
            <div class="relative">
                <button @click="open = !open" @click.outside="open = false" class="focus:outline-none">
                    <i class="fas fa-user-circle text-xl text-[#FE482B] cursor-pointer"></i>
                </button>
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                    style="top: 100%;">
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-question-circle mr-2 text-[#FE482B]"></i> Bantuan
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        @csrf
                        <button type="submit" class="">
                            <i class="fas fa-sign-out-alt mr-2 text-[#FE482B]"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</nav>
    

    {{-- Isi konten --}}
    <main class="mt-20">
        {{ $slot }}
    </main>

    <footer class="bg-black text-white py-4 px-24">
        <div class="flex flex-col md:flex-row items-center md:justify-between text-center w-full">
            <img class="w-32" src="{{ asset('images/SidataLogoWhite.png') }}" alt="">
            <p class="text-sm mt-2 md:mt-0 w-full md:w-auto">© 2025 SiData. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
