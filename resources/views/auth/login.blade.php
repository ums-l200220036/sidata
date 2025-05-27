<x-guest-layout>
    <div class="min-h-screen flex">
        <!-- Left Section -->
        <div class="w-2/3 bg-white flex items-center justify-center relative">
            <div class="w-full h-screen relative">
                <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-end items-center text-center">
                    <h1 class="text-5xl font-bold mb-36 z-10 bg-white bg-opacity-80 px-4 py-2 rounded text-left">
                        Selamat Datang Di <br> <span class="text-[#FE482B]">SiData</span> Surakarta
                    </h1>
                    <img src="{{ asset('images/jumbotronimage.png') }}"
                         alt="Ilustrasi Surakarta"
                         class="w-full max-h-[80vh] object-cover" />
                </div>
            </div>
        </div>

        <!-- Right Section (Login) -->
        <div class="w-1/3 bg-[#FE482B] flex items-center justify-center h-screen">
            <div class="w-full max-w-sm px-6">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <h2 class="text-white text-4xl font-semibold mb-6 text-center">Login</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-white text-sm mb-1">Nama Pengguna</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                               class="w-full px-4 py-2 rounded bg-white text-gray-800 focus:outline-none"
                               placeholder="Masukkan Nama Pengguna">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-white" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-white text-sm mb-1">Sandi</label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-2 rounded bg-white text-gray-800 focus:outline-none"
                               placeholder="Masukkan Sandi">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-white" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center text-white text-sm">
                            <input type="checkbox" name="remember" class="mr-2">
                            Ingat Saya
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-white text-sm underline hover:text-gray-300"
                               href="{{ route('password.request') }}">
                                Pusat Bantuan ?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                                class="w-full bg-white text-[#FE482B] font-semibold px-4 py-2 rounded hover:bg-[#FE482B] hover:text-white hover:border hover:border-white transition">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
