@extends('layouts.app')

@section('title', 'Tentang SiData Surakarta')

<x-navbar>
  <section class="bg-white pt-28 pb-32 px-6 md:px-20 lg:px-40 xl:px-64">
    <div class="max-w-full mx-auto">

      <!-- Logo -->
      <div class="flex justify-center mb-16">
        <img src="{{ asset('images/SidataLogo.png') }}" alt="Logo SiData Surakarta" class="h-24 w-auto" loading="lazy" />
      </div>

      <!-- Deskripsi singkat -->
      <p class="text-gray-700 text-base md:text-lg max-w-4xl mx-auto leading-relaxed mb-20 font-sans text-center">
        SiData adalah platform data terbuka milik Pemerintah Kota Surakarta yang menyediakan informasi strategis bagi publik, akademisi, dan pelaku pemerintahan. Platform ini bertujuan untuk mendorong transparansi, partisipasi, dan inovasi berbasis data untuk kemajuan kota.
      </p>

      <!-- Grid Informasi -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10 max-w-full">

        <!-- Latar Belakang -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-book-open text-[#FE482B] text-xl"></i> Latar Belakang
          </h2>
          <p class="text-gray-800 leading-relaxed text-sm md:text-base text-left">
            Di era digital, data merupakan aset penting yang mendukung pengambilan keputusan efektif. Pemerintah Kota Surakarta membangun SiData untuk menyediakan akses data yang terbuka, akurat, dan terpercaya bagi semua lapisan masyarakat.
          </p>
        </article>

        <!-- Tujuan -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-bullseye text-[#FE482B] text-xl"></i> Tujuan
          </h2>
          <ul class="list-disc list-inside text-gray-800 space-y-2 text-sm md:text-base text-left">
            <li>Menyediakan data publik yang transparan dan mudah diakses.</li>
            <li>Mendorong partisipasi aktif masyarakat dalam pembangunan kota.</li>
            <li>Mendukung pengambilan kebijakan berbasis bukti.</li>
            <li>Menjadi sumber data utama untuk riset dan inovasi lokal.</li>
          </ul>
        </article>

        <!-- Manfaat -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-hand-holding-heart text-[#FE482B] text-xl"></i> Manfaat
          </h2>
          <ul class="text-gray-800 space-y-3 text-sm md:text-base text-left">
            <li><strong>Masyarakat:</strong> Akses informasi kota dengan mudah dan cepat.</li>
            <li><strong>Akademisi:</strong> Dukungan data untuk kajian dan penelitian mendalam.</li>
            <li><strong>Pemerintah:</strong> Data sebagai dasar evaluasi dan kebijakan tepat sasaran.</li>
            <li><strong>Startup & UMKM:</strong> Temukan peluang usaha berbasis data nyata.</li>
          </ul>
        </article>

        <!-- Pengembang -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300 md:col-span-2 xl:col-span-1">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-users-cog text-[#FE482B] text-xl"></i> Pengembang
          </h2>
          <p class="text-gray-800 mb-4 text-sm md:text-base text-left">
            SiData dikembangkan oleh tim Teknologi Informasi Pemerintah Kota Surakarta bersama akademisi dan komunitas teknologi lokal, dengan fokus pada kemudahan penggunaan dan dampak positif.
          </p>
          <p class="text-gray-800 mb-3 text-sm md:text-base text-left">
            Sebagai bagian kolaborasi akademik, mahasiswa <strong>Teknik Informatika Universitas Muhammadiyah Surakarta</strong> turut berkontribusi, yaitu:
          </p>
          <ul class="list-disc list-inside text-gray-900 font-semibold text-sm md:text-base space-y-1 text-left">
            <li>Yakub Firman Mustofa</li>
            <li>Inandra Asha Fardhana</li>
            <li>Achmad Zaki Ramadhani</li>
          </ul>
        </article>

        <!-- Cara Mengakses Data -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300 md:col-span-2">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-download text-[#FE482B] text-xl"></i> Cara Mengakses Data
          </h2>
          <p class="text-gray-800 mb-4 text-sm md:text-base text-left">
            Untuk mengakses data di SiData, pengguna dapat:
          </p>
          <ul class="list-disc list-inside text-gray-800 space-y-2 text-sm md:text-base text-left">
            <li>Menjelajah dashboard interaktif pada situs resmi kami dengan filter data yang lengkap.</li>
            <li>Mengunduh dataset dalam format CSV, JSON, atau API untuk integrasi lebih lanjut.</li>
            <li>Mendaftar untuk mendapatkan akses API bagi pengembang dan peneliti.</li>
            <li>Memanfaatkan dokumentasi dan tutorial yang tersedia untuk memudahkan penggunaan data.</li>
          </ul>
          <p class="text-gray-800 mt-4 text-sm md:text-base text-left">
            Kami berkomitmen menyediakan data yang mudah diakses dan bermanfaat bagi semua pihak.
          </p>
        </article>

        <!-- Keamanan dan Privasi -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-shield-alt text-[#FE482B] text-xl"></i> Keamanan dan Privasi
          </h2>
          <p class="text-gray-800 text-sm md:text-base leading-relaxed text-left">
            SiData mengutamakan keamanan data dan perlindungan privasi pengguna dengan:
          </p>
          <ul class="list-disc list-inside text-gray-800 space-y-2 mt-3 text-sm md:text-base text-left">
            <li>Menerapkan standar keamanan tingkat tinggi untuk melindungi data dari akses tidak sah.</li>
            <li>Mengelola data pribadi sesuai dengan peraturan perlindungan data yang berlaku.</li>
            <li>Memastikan semua data yang dipublikasikan telah melalui proses anonymisasi dan verifikasi.</li>
            <li>Memberikan edukasi kepada pengguna tentang penggunaan data yang bertanggung jawab.</li>
          </ul>
          <p class="text-gray-800 mt-4 text-sm md:text-base text-left">
            Komitmen ini untuk menjaga kepercayaan dan integritas layanan kami.
          </p>
        </article>

        <!-- Fitur Utama -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300 md:col-span-1">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-3 justify-start">
            <i class="fas fa-cogs text-[#FE482B] text-xl"></i> Fitur Utama
          </h2>
          <ul class="list-disc list-inside text-gray-800 space-y-3 text-sm md:text-base text-left">
            <li>Dashboard interaktif dengan visualisasi data dinamis.</li>
            <li>Filter dan pencarian data yang mudah digunakan.</li>
            <li>API terbuka untuk integrasi dan pengembangan aplikasi.</li>
            <li>Update data secara real-time dan berkala.</li>
          </ul>
        </article>

        <!-- Hubungi Kami -->
        <article class="bg-white rounded-lg shadow-sm p-8 border border-gray-100 hover:shadow-md transition-shadow duration-300 md:col-span-1">
          <h2 class="text-xl font-semibold text-[#FE482B] mb-6 flex items-center gap-3 justify-start">
            <i class="fas fa-envelope text-[#FE482B] text-xl"></i> Hubungi Kami
          </h2>
          <p class="text-gray-800 mb-3 text-sm md:text-base text-left">
            Untuk pertanyaan, saran, atau kerjasama, silakan hubungi kami melalui:
          </p>
          <ul class="text-gray-800 space-y-2 text-sm md:text-base text-left">
            <li><strong>Email:</strong> info@sidata.surakarta.go.id</li>
            <li><strong>Telepon:</strong> (0271) 123-4567</li>
            <li><strong>Alamat:</strong> Jl. Slamet Riyadi No. 123, Surakarta</li>
          </ul>
        </article>

      </div>
    </div>
  </section>
</x-navbar>
