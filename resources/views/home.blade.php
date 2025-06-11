@extends('layouts.app')

@section('title', 'Home')

<x-navbar>
    <div class="relative bg-white pt-16 md:pt-20 lg:pt-24 pb-20 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <p class="text-[#FE482B] text-base md:text-lg font-semibold uppercase tracking-wide">Kulonuwun</p>
            <h1 class="text-4xl md:text-5xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4">
                Selamat Datang Di <span class="text-[#FE482B]">SiData</span> Surakarta
            </h1>
            <p class="text-gray-700 text-base md:text-md max-w-4xl mx-auto leading-relaxed mb-8">
                Temukan berbagai data terbuka Kota Surakarta yang transparan, akurat, dan mudah diakses. SiData hadir untuk mendukung partisipasi publik, mendorong inovasi, dan memfasilitasi pengambilan keputusan berbasis data yang solid. Jelajahi informasi vital seputar kependudukan, ekonomi, pendidikan, infrastruktur, dan beragam sektor lainnya dalam satu platform yang terintegrasi penuh.
            </p>
        </div>

        <div class="relative w-full overflow-hidden mt-[-80px] md:mt-[-100px] lg:mt-[-120px]">
            <img src="{{ asset('images/jumbotronimage.png') }}"
                 alt="Jumbotron Image"
                 class="w-full h-auto object-cover" />
        </div>
    </div>

    <section class="pb-16 md:pb-20 text-center">
        <p class="text-[#FE482B] text-sm md:text-base font-semibold uppercase tracking-wide mb-2">Statistik Data</p>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12">Total Seluruh Data di SiData</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg border-b-4 border-[#FE482B] hover:shadow-xl transition-all duration-300 p-8 text-left flex flex-col justify-between">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-database text-[#FE482B] text-4xl mr-4"></i> <p class="text-5xl font-extrabold text-gray-900">2516</p>
                    </div>
                    <p class="font-bold text-xl mt-3 text-gray-800">Total Dataset</p>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        Kumpulan data mentah yang terstruktur dalam bentuk tabel, siap untuk diolah dan dianalisis lebih lanjut oleh publik.
                    </p>
                </div>
                <a href="#" class="inline-block mt-6 px-6 py-3 bg-[#FE482B] text-white text-base font-semibold rounded-md shadow-md hover:bg-[#e5401f] transition-colors duration-300 self-start">Lihat Dataset</a>
            </div>

            <div class="bg-white shadow-lg border-b-4 border-[#FE482B] hover:shadow-xl transition-all duration-300 p-8 text-left flex flex-col justify-between">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-layer-group text-[#FE482B] text-4xl mr-4"></i> <p class="text-5xl font-extrabold text-gray-900">10</p>
                    </div>
                    <p class="font-bold text-xl mt-3 text-gray-800">Total Kategori Data</p>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        Pengelompokan informasi berdasarkan tema atau sektor tertentu untuk memudahkan penelusuran dan pemahaman data.
                    </p>
                </div>
                <a href="#" class="inline-block mt-6 px-6 py-3 bg-[#FE482B] text-white text-base font-semibold rounded-md shadow-md hover:bg-[#e5401f] transition-colors duration-300 self-start">Telusuri Kategori</a>
            </div>
        </div>
    </section>
</x-navbar>