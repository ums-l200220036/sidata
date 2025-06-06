@extends('layouts.app')

@section('title', 'Home')
<x-navbar>
    <div class="relative text-center py-16 bg-white">
        <div class="max-w-4xl mx-auto">
            <p class="text-[#FE482B] text-base font-medium">Kulonuwun</p>
            <h1 class="text-4xl font-bold mt-2">
                Selamat Datang Di <span class="text-[#FE482B]">SiData</span> Surakarta
            </h1>
            <p class="text-gray-700 mt-4 text-base leading-relaxed">
                Temukan berbagai data terbuka Kota Surakarta yang transparan, akurat, dan mudah diakses untuk mendukung partisipasi publik, inovasi, dan pengambilan keputusan berbasis data. Jelajahi informasi seputar kependudukan, ekonomi, pendidikan, infrastruktur, dan sektor lainnya dalam satu platform terintegrasi.
            </p>
        </div>

        <img src="{{ asset('images/jumbotronimage.png') }}"
             alt="Jumbotron Image"
             class="w-full h-auto -mt-20" />
    </div>

    <section class="py-5 bg-white text-center">
        <p class="text-[#FE482B] text-sm font-medium">Statistik Data</p>
        <h2 class="text-3xl font-bold mb-10">Total Seluruh Data</h2>
    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">
            <!-- Kartu 1 -->
            <div class="bg-gray-100 rounded-lg p-6 shadow-sm text-left">
                <p class="text-3xl font-bold">2516</p>
                <p class="font-semibold mt-1">Total Dataset</p>
                <p class="text-sm text-gray-600 mt-2">Kumpulan data mentah berupa tabel yang dapat diolah lebih lanjut</p>
                <a href="#" class="inline-block mt-4 px-4 py-2 bg-[#FE482B] text-white text-sm rounded hover:bg-[#e5401f]">Selengkapnya</a>
            </div>
    
            <!-- Kartu 2 -->
            <div class="bg-gray-100 rounded-lg p-6 shadow-sm text-left">
                <p class="text-3xl font-bold">10</p>
                <p class="font-semibold mt-1">Total Group</p>
                <p class="text-sm text-gray-600 mt-2">Gambaran informasi tertentu yang sudah dikelompokkan berdasarkan group</p>
                <a href="#" class="inline-block mt-4 px-4 py-2 bg-[#FE482B] text-white text-sm rounded hover:bg-[#e5401f]">Selengkapnya</a>
            </div>
    
            <!-- Kartu 3 -->
            <div class="bg-gray-100 rounded-lg p-6 shadow-sm text-left">
                <p class="text-3xl font-bold">37</p>
                <p class="font-semibold mt-1">Total Organisasi</p>
                <p class="text-sm text-gray-600 mt-2">Organisasi Perangkat Daerah yang publikasi datanya tampil di Solodata</p>
                <a href="#" class="inline-block mt-4 px-4 py-2 bg-[#FE482B] text-white text-sm rounded hover:bg-[#e5401f]">Selengkapnya</a>
            </div>
        </div>
    </section>
    
</x-navbar>