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
    @vite('resources/css/app.css')
</head>
<body>
    <nav class="bg-white px-24 py-4 flex items-center justify-between relative"
            x-data="{ activePage: window.location.pathname }">

            <!-- Logo -->
            <div class="text-[#FE482B] font-bold text-2xl">SiData</div>

            <!-- Menu -->
            <ul class="flex space-x-6 text-gray-800 font-medium">
                <li>
                    <a href="{{ route('beranda') }}"
                    :class="activePage === '{{ route('beranda', absolute: false) }}' 
                                ? 'text-black border-b-2 pb-0.5 border-[#FE482B]' 
                                : 'text-black hover:text-[#FE482B]'"
                    class="transition">
                    Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('unggah') }}"
                    :class="activePage.startsWith('{{ route('unggah', absolute: false) }}') 
                                ? 'text-black border-b-2 pb-0.5 border-[#FE482B]' 
                                : 'text-black hover:text-[#FE482B]'"
                    class="transition">
                    Unggah Data
                    </a>
                </li>
            </ul>

            <!-- Ikon User Dropdown (tidak berubah) -->
            <div class="flex items-center space-x-2 relative" x-data="{ open: false }">
                <span class="font-medium">Dinas Kesehatan</span>
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
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2 text-[#FE482B]"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>

    <main class="">
        {{ $slot }}
    </main>
    
      
    <footer class="bg-black text-white py-4 px-24">
        <div class="flex flex-col md:flex-row items-center md:justify-between text-center w-full">
            <h1 class="text-lg font-bold">SiData</h1>
            <p class="text-sm mt-2 md:mt-0 w-full md:w-auto">© 2025 SiData. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>