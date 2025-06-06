@extends('layouts.app')

@section('title', 'Register')
<x-navbar>
    <div class="w-full flex items-center justify-center bg-gray-100 px-4 py-10">
        <div class="w-full max-w-xl bg-white rounded-xl shadow-md p-8">
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-[#FE482B]">Tambah User</h2>
                <p class="text-gray-600 text-sm">Hanya admin yang dapat menambahkan pengguna baru melalui form ini.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nama Lengkap')" class="text-sm text-gray-700" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                        class="block w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-[#FE482B] focus:border-[#FE482B]" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-600" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-sm text-gray-700" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required
                        class="block w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-[#FE482B] focus:border-[#FE482B]" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Kata Sandi')" class="text-sm text-gray-700" />
                    <x-text-input id="password" type="password" name="password" required
                        class="block w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-[#FE482B] focus:border-[#FE482B]" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-sm text-gray-700" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                        class="block w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-[#FE482B] focus:border-[#FE482B]" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-600" />
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-[#FE482B]">
                        Sudah punya akun?
                    </a>

                    <button class="bg-[#FE482B] hover:bg-orange-600 text-white px-6 py-2 rounded-md">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-navbar>
