<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <title>Admin Panel</title>
  <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="text-gray-800 font-inter">

  <!-- Sidenav -->
  <div class="fixed left-0 top-0 w-64 h-full bg-white p-4 z-50">
    <a href="#" class="flex items-center justify-center pb-4">
      <h2 class="font-bold text-2xl"><span class="text-[#FE482B]">SiData</span> Surakarta</h2>
    </a>
    <ul class="mt-4">
      <li class="mb-1">
        <a href="#" class="flex font-semibold items-center py-2 px-4 bg-[#FE482B] text-white rounded-md">
          <i class="ri-home-2-line mr-3 text-lg"></i>
          <span class="text-sm">Dashboard</span>
        </a>
      </li>
      <li class="mb-1">
        <a href="#" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-[#FE482B] hover:text-white rounded-md">
          <i class='bx bx-user mr-3 text-lg'></i>                
          <span class="text-sm">Users</span>
          {{-- <i class="ri-arrow-right-s-line ml-auto"></i> --}}
        </a>
        {{-- <ul class="pl-7 mt-2 hidden">
          <li class="mb-4">
            <a href="#" class="text-gray-900 text-sm flex items-center hover:text-[#FE482B] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">All</a>
          </li>
          <li class="mb-4">
            <a href="#" class="text-gray-900 text-sm flex items-center hover:text-[#FE482B] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Roles</a>
          </li>
        </ul> --}}
      </li>
    </ul>
  </div>

  <!-- Main content -->
  <main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-200 min-h-screen transition-all">
    <!-- Navbar -->
    <div class="py-2 px-6 bg-white flex items-center shadow sticky top-0 z-30">
      <ul class="ml-auto flex items-center">
        <li class="relative ml-3" x-data="{ open: false }">
          <button class="flex items-center" @click="open = !open">
            <div class="w-10 h-10 relative">
              <div class="p-1 bg-gray-100 rounded-full">
                {{-- <img class="w-8 h-8 rounded-full" src="https://cdn-icons-png.flaticon.com/512/8500/8500156.png" alt="User" /> --}}
                <svg class="w-8 h-8 rounded-full" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                      <path d="M 45 0 C 20.147 0 0 20.147 0 45 c 0 24.853 20.147 45 45 45 s 45 -20.147 45 -45 C 90 20.147 69.853 0 45 0 z M 45 22.007 c 8.899 0 16.14 7.241 16.14 16.14 c 0 8.9 -7.241 16.14 -16.14 16.14 c -8.9 0 -16.14 -7.24 -16.14 -16.14 C 28.86 29.248 36.1 22.007 45 22.007 z M 45 83.843 c -11.135 0 -21.123 -4.885 -27.957 -12.623 c 3.177 -5.75 8.144 -10.476 14.05 -13.341 c 2.009 -0.974 4.354 -0.958 6.435 0.041 c 2.343 1.126 4.857 1.696 7.473 1.696 c 2.615 0 5.13 -0.571 7.473 -1.696 c 2.083 -1 4.428 -1.015 6.435 -0.041 c 5.906 2.864 10.872 7.591 14.049 13.341 C 66.123 78.957 56.135 83.843 45 83.843 z"
                        style="fill: #FE482B;" />
                    </g>
                  </svg>                  
                <div class="absolute top-0 left-7 w-3 h-3 bg-lime-400 border-2 border-white rounded-full animate-ping"></div>
                <div class="absolute top-0 left-7 w-3 h-3 bg-lime-500 border-2 border-white rounded-full"></div>
              </div>
            </div>
            <div class="p-2 hidden md:block text-left">
              <h2 class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
              <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
            </div>
          </button>
          <ul x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-md py-1.5 z-50">
            <li><a href="#" class="block text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#FE482B] hover:bg-gray-50">Profile</a></li>
            <li><a href="#" class="block text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#FE482B] hover:bg-gray-50">Settings</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                    <button type="submit" class="block text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#FE482B] hover:bg-gray-50 cursor-pointer">
                        Log Out
                    </button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- End Navbar -->
  </main>
</body>
</html>
