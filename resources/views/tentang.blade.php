@extends('layouts.app')

@section('title', 'Tentang SiData Surakarta')

<x-navbar>
    <section class="bg-gray-50 pt-28 pb-32 px-6 md:px-20 lg:px-40 xl:px-64">
        <div class="max-w-full mx-auto">

            <div class="text-center mb-20">
                <img src="{{ asset('images/SidataLogo.png') }}" alt="Logo SiData Surakarta" class="h-28 w-auto mx-auto mb-8" loading="lazy" />
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Mengenal <span class="text-[#FE482B]">SiData Surakarta</span>
                </h1>
                <p class="text-gray-600 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed font-light">
                    SiData Kota Surakarta adalah wujud komitmen Pemerintah Kota Surakarta dalam mewujudkan pemerintahan yang transparan dan berbasis data. Sebagai platform data terbuka, kami menyediakan akses mudah ke informasi strategis, mendorong partisipasi aktif, dan menjadi katalisator inovasi demi kemajuan kota yang berkelanjutan.
                </p>
            </div>


            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">Misi dan Nilai Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-eye text-[#FE482B] text-2xl"></i> Transparansi
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-base">
                            Kami percaya pada kekuatan informasi yang terbuka. SiData memastikan setiap warga memiliki hak untuk mengakses data publik, menumbuhkan kepercayaan dan akuntabilitas pemerintah.
                        </p>
                    </article>
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-handshake text-[#FE482B] text-2xl"></i> Kolaborasi
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-base">
                            Inovasi lahir dari kerja sama. Kami aktif mendorong partisipasi masyarakat, akademisi, dan sektor swasta untuk bersama-sama memanfaatkan data demi solusi kota yang lebih baik.
                        </p>
                    </article>
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-chart-line text-[#FE482B] text-2xl"></i> Inovasi Berbasis Data
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-base">
                            Data bukan sekadar angka, melainkan fondasi inovasi. SiData hadir untuk memicu riset, pengembangan aplikasi, dan pengambilan keputusan yang didasari bukti kuat dan analisis mendalam.
                        </p>
                    </article>
                </div>
            </div>


            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">Apa yang Kami Tawarkan?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-4xl mx-auto">
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-globe text-[#FE482B] text-2xl"></i> Dashboard Interaktif
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-base">
                            Visualisasikan data Surakarta dengan dashboard yang intuitif dan mudah dipahami. Filter informasi sesuai kebutuhan Anda dan dapatkan insight secara real-time.
                        </p>
                    </article>
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-code text-[#FE482B] text-2xl"></i> Open API & Data Download
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-base">
                            Untuk pengembang dan peneliti, kami menyediakan API terbuka dan opsi unduh data dalam berbagai format (CSV, JSON). Integrasikan data kami ke dalam aplikasi atau riset Anda dengan mudah.
                        </p>
                    </article>
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-sync-alt text-[#FE482B] text-2xl"></i> Data Akurat dan Terkini
                        </h3>
                        <p class="text-700 leading-relaxed text-base">
                            Kami berkomitmen untuk menyediakan data yang akurat, valid, dan selalu terbarui. Informasi di SiData melewati proses verifikasi ketat untuk menjaga kualitas.
                        </p>
                    </article>
                    <article class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                            <i class="fas fa-shield-alt text-[#FE482B] text-2xl"></i> Keamanan dan Privasi
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-base">
                            Keamanan data dan privasi pengguna adalah prioritas tertinggi. Kami menerapkan standar keamanan canggih dan mematuhi regulasi perlindungan data yang berlaku.
                        </p>
                    </article>
                </div>
            </div>



            <div class="mb-20 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-12">Tim di Balik SiData</h2>
                <p class="text-gray-700 max-w-3xl mx-auto mb-8 leading-relaxed text-lg">
                    SiData dikembangkan melalui kolaborasi erat antara Tim Teknologi Informasi DISKOMINFO Pemerintah Kota Surakarta dengan dukungan berharga dari akademisi. Kami bangga dengan kontribusi dari talenta muda:
                </p>
                <div class="inline-block bg-white shadow-md p-6 border-l-8 border-[#FE482B] text-left">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Mahasiswa Teknik Informatika Universitas Muhammadiyah Surakarta:</h3>
                    <ul class="list-disc list-inside text-gray-800 space-y-1 text-base">
                        <li>Yakub Firman Mustofa</li>
                        <li>Inandra Asha Fardhana</li>
                        <li>Achmad Zaki Ramadhani</li>
                    </ul>
                </div>
            </div>



            <div class="text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-12">Terhubung dengan Kami</h2>
                <p class="text-gray-700 max-w-2xl mx-auto mb-8 leading-relaxed text-lg">
                    Kami selalu terbuka untuk pertanyaan, saran, atau kolaborasi. Jangan ragu untuk menghubungi tim SiData melalui saluran berikut:
                </p>
                <div class="bg-white shadow-lg p-8 border-t-8 border-[#FE482B] inline-block text-left max-w-lg w-full">
                    <ul class="text-gray-800 space-y-4 text-lg">
                        <li>
                            <strong class="flex items-center gap-3">
                                <i class="fas fa-envelope text-[#FE482B] text-2xl"></i> Email:
                            </strong>
                            <a href="mailto:info@sidata.surakarta.go.id" class="text-blue-600 hover:underline ml-9">info@sidata.surakarta.go.id</a>
                        </li>
                        <li>
                            <strong class="flex items-center gap-3">
                                <i class="fas fa-phone-alt text-[#FE482B] text-2xl"></i> Telepon:
                            </strong>
                            <a href="tel:+622711234567" class="text-blue-600 hover:underline ml-9">(0271) 123-4567</a>
                        </li>
                        <li>
                            <strong class="flex items-center gap-3">
                                <i class="fas fa-map-marker-alt text-[#FE482B] text-2xl"></i> Alamat:
                            </strong>
                            <span class="ml-9 block">Jl. Slamet Riyadi No. 123, Surakarta</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </section>
</x-navbar>