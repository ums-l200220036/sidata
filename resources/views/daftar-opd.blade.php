@extends('layouts.app')

@section('title', 'Daftar OPD')

{{-- Assume you have a x-navbar component or remove it if not needed --}}
<x-navbar>
    <main class="px-10 py-8">

        {{-- Hero/Introduction Section --}}
        <div class="bg-white p-8 mb-12 gap-10 flex flex-col md:flex-row items-end">
            <div class="mt-8 md:mt-0 flex justify-center items-center">
                <div class="w-full" style="aspect-ratio: 6 / 3; overflow: hidden;">
                    <img src="{{ asset('images/jumbotronimage.png') }}" alt="Jumbotron Image" class="w-full h-full object-cover object-left">
                </div>
            </div>
            <div class=" gap-4">
                {{-- Judul dan Deskripsi --}}
                <h1 class="font-semibold text-3xl mb-4">Daftar OPD</h1>
                <p class="text-gray-600 text-xs font-normal text-justify leading-relaxed pr-32">
                    SiData (Sistem Informasi Data Terpadu) Kota Surakarta adalah publikasi rutin tahunan Pemerintah Kota Surakarta. Publikasi ini menyajikan data statistik sektoral yang komprehensif untuk berbagai bidang penting, seperti geografi, iklim, pemerintahan, kependudukan, ketenagakerjaan, sosial, kesejahteraan rakyat, pertanian, industri, pertambangan, energi, pariwisata, transportasi, komunikasi, perbankan, koperasi, harga-harga, pengeluaran penduduk, perdagangan, dan sistem neraca regional.
                </p>
            </div>
        </div>

        {{-- Logika data langsung di Blade --}}
        <?php
            $dinases = [
                // DINAS
                [ 'name' => 'Dinas Pendidikan', 'icon_class' => 'fa-solid fa-graduation-cap', 'link' => '#' ],
                [ 'name' => 'Dinas Kesehatan', 'icon_class' => 'fa-solid fa-heartbeat', 'link' => '#' ],
                [ 'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'icon_class' => 'fa-solid fa-hard-hat', 'link' => '#' ],
                [ 'name' => 'Dinas Sosial', 'icon_class' => 'fa-solid fa-hands-helping', 'link' => '#' ],
                [ 'name' => 'Dinas Lingkungan Hidup', 'icon_class' => 'fa-solid fa-recycle', 'link' => '#' ],
                [ 'name' => 'Dinas Administrasi Kependudukan dan Pencatatan Sipil', 'icon_class' => 'fa-solid fa-address-card', 'link' => '#' ],
                [ 'name' => 'Dinas Pemberdayaan Perempuan Dan Pelindungan Anak Serta Pengendalian Penduduk Dan Keluarga Berencana', 'icon_class' => 'fa-solid fa-users', 'link' => '#' ],
                [ 'name' => 'Dinas Perhubungan', 'icon_class' => 'fa-solid fa-car', 'link' => '#' ],
                [ 'name' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian', 'icon_class' => 'fa-solid fa-globe', 'link' => '#' ],
                [ 'name' => 'Dinas Koperasi, Usaha Kecil & Menengah dan Perindustrian', 'icon_class' => 'fa-solid fa-industry', 'link' => '#' ],
                [ 'name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu', 'icon_class' => 'fa-solid fa-dollar-sign', 'link' => '#' ],
                [ 'name' => 'Dinas Kepemudaan dan Olah Raga', 'icon_class' => 'fa-solid fa-running', 'link' => '#' ],
                [ 'name' => 'Dinas Perpustakaan dan Kearsipan', 'icon_class' => 'fa-solid fa-book-reader', 'link' => '#' ],
                [ 'name' => 'Dinas Perdagangan', 'icon_class' => 'fa-solid fa-store', 'link' => '#' ],
                [ 'name' => 'Dinas Pemadam Kebakaran', 'icon_class' => 'fa-solid fa-fire-extinguisher', 'link' => '#' ],
                [ 'name' => 'Dinas Tenaga Kerja', 'icon_class' => 'fa-solid fa-briefcase', 'link' => '#' ],
                [ 'name' => 'Dinas Pertanian, Ketahanan Pangan dan Perikanan', 'icon_class' => 'fa-solid fa-leaf', 'link' => '#' ],
                [ 'name' => 'Dinas Kebudayaan Dan Pariwisata', 'icon_class' => 'fa-solid fa-landmark', 'link' => '#' ],

                // BADAN
                [ 'name' => 'Badan Pengelola Keuangan Dan Aset Daerah', 'icon_class' => 'fa-solid fa-wallet', 'link' => '#' ],
                [ 'name' => 'Badan Kepegawaian Dan Pengembangan Sumber Daya Manusia', 'icon_class' => 'fa-solid fa-user-tie', 'link' => '#' ],
                [ 'name' => 'Badan Kesatuan Bangsa Dan Politik', 'icon_class' => 'fa-solid fa-flag-usa', 'link' => '#' ],
                [ 'name' => 'Badan Penanggulangan Bencana Daerah', 'icon_class' => 'fa-solid fa-house-damage', 'link' => '#' ],

                // SEKRETARIAT & LAINNYA
                [ 'name' => 'Bagian Hukum', 'icon_class' => 'fa-solid fa-gavel', 'link' => '#' ],
                [ 'name' => 'Bagian Tata Pemerintahan', 'icon_class' => 'fa-solid fa-sitemap', 'link' => '#' ],
                [ 'name' => 'Sekretariat DPRD', 'icon_class' => 'fa-solid fa-balance-scale', 'link' => '#' ],
                [ 'name' => 'Satuan Polisi Pamong Praja', 'icon_class' => 'fa-solid fa-shield-alt', 'link' => '#' ],
            ];
        ?>

        {{-- Hero Section --}}

        {{-- Dinas Cards Section --}}
        <div class="grid grid-cols-4 md:grid-cols-4 lg:grid-cols-4  w-full gap-8 mb-12 px-8">
        
            @foreach($dinases as $dinas)
                <a href="{{ $dinas['link'] }}"
                x-data="{ isHovering: false }"
                @mouseenter="isHovering = true"
                @mouseleave="isHovering = false"
                :class="{ 'bg-[#FE482B] text-white shadow': isHovering, 'bg-white text-gray-800 shadow-sm border-gray-300': !isHovering }"
                class="block border border-1 rounded-md p-6 text-center transition-all duration-300">
                    <div class="text-3xl mb-4">
                        <i class="{{ $dinas['icon_class'] }}" :class="{ 'text-white': isHovering, 'text-[#FE482B]': !isHovering }"></i>
                    </div>
                    <h3 class="text-xs font-semibold">{{ $dinas['name'] }}</h3>
                </a>
            @endforeach
        </div>
    </main>
</x-navbar>