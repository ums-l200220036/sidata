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
        <!-- Logo -->
        <div class="text-[#FE482B] font-bold text-2xl">
          SiData
        </div>
      
        <!-- Menu -->
        <ul class="flex space-x-6 text-gray-800 font-medium">
          <li>
            <a href="#" class="text-black border-b-2 transition border-[#FE482B]">Beranda</a>
          </li>
          <li>
            <a href="#" class="text-black transition">Tentang</a>
          </li>
        </ul>
      
        <!-- Tombol Login -->
        <a href="#" class="bg-[#FE482B] hover:bg-[#FE482B] text-white font-semibold px-5 py-1 rounded-xl">
          Login
        </a>
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