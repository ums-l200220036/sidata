<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
</head>
<body>
      <nav class="bg-white px-24 py-4 flex items-center justify-between">
        <div class="text-[#FE482B] font-bold text-2xl">
          SiData
        </div>
      
        <ul class="flex space-x-6 text-gray-800 font-medium">
          {{-- Link Beranda selalu tampil untuk semua kondisi --}}
          <li>
            <a href="/" class="text-black border-b-2 transition border-[#FE482B]">Beranda</a>
          </li>

          @guest
            {{-- Menu untuk pengguna yang belum login --}}
            <li>
              <a href="/tentang" class="text-black transition">Tentang</a>
            </li>
          @endguest

          @auth
            {{-- Menu untuk pengguna yang sudah login --}}
            @if (Auth::user()->role == 'opd')
              <li>
                <a href="/unggah-data" class="text-black transition">Unggah Data</a>
              </li>
            @elseif (Auth::user()->role == 'kelurahan')
              <li>
                <a href="/data-kelurahan" class="text-black transition">Data Kelurahan</a>
              </li>
            @endif
          @endauth
        </ul>
      
        @guest
          {{-- Tampilkan tombol Login jika belum login --}}
          <a href="/login" class="bg-[#FE482B] hover:bg-orange-600 text-white font-semibold px-5 py-1 rounded-xl transition">
            Login
          </a>
        @endguest

        @auth
          {{-- Tampilkan nama pengguna dan tombol Logout jika sudah login --}}
          <div class="flex items-center space-x-4">
            <span class="text-gray-800 font-medium">Halo, {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold px-5 py-1 rounded-xl transition">
                Logout
              </button>
            </form>
          </div>
        @endauth
    </nav>

      {{ $slot }}
      

      <footer class="bg-black text-white py-4 mt-7 px-24">
        <div class="flex flex-col md:flex-row items-center md:justify-between text-center w-full">
            <h1 class="text-lg font-bold">SiData</h1>
            <p class="text-sm mt-2 md:mt-0 w-full md:w-auto">© 2025 SiData. All rights reserved.</p>
        </div>
      </footer>
</body>
</html>