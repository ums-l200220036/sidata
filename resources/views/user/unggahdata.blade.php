<x-navbar>
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-3 bg-cover bg-no-repeat bg-right">
        <!-- Form Section -->
        <div class="pl-24 bg-white shadow-2xl shadow-gray-400/50">
            <div class="md:col-span-1 flex flex-col justify-center bg-white bg-opacity-90 p-10 pl-0">
                <form action="{{ route('data.import') }}" method="POST" enctype="multipart/form-data" x-data="{ fileName: '' }">
                    @csrf

                    <h1 class="text-2xl font-bold text-gray-900 mb-4">
                        Unggah File Excel Data Sektoral / Statistik OPD
                    </h1>

                    <!-- Tambahkan pemilihan indikator jika diperlukan -->
                    <label for="indikator_id" class="block font-semibold mb-2 text-gray-700">
                        Pilih Indikator:
                    </label>
                    <select name="indikator_id"
                            class="w-full mb-6 px-4 py-3 border border-[#FE482B] rounded-xl shadow-sm text-gray-700 font-medium bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                            required>
                        @foreach($indikatorList as $indikator)
                            <option value="{{ $indikator->id }}">{{ $indikator->nama_indikator }}</option>
                        @endforeach
                    </select>

                    <p class="text-sm text-gray-600 mb-4">
                        Sebelum mengunggah data, harap pilih indikator terlebih dahulu, lalu unduh template Excel berikut sebagai panduan pengisian data sektoral atau statistik OPD Kota Surakarta:
                    </p>

                    <a href="#"
                       class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r bg-[#FE482B] text-white hover:outline-1 hover:outline hover:bg-white  hover:text-[#FE482B] rounded-lg shadow-lg font-semibold mb-6 transition duration-300">
                        <i class="fas fa-download"></i> Unduh Template Excel
                    </a>

                    <!-- Upload Input -->
                    <label for="upload" class="block mb-2 font-semibold text-gray-700">
                        Unggah File Excel: <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-600 mb-2">
                        Setelah data terisi sesuai format, silahkan unggah file Excel Anda di bawah ini.<br>
                        Format file: .xlsx atau .xls<br>
                        Penamaan file: Nama_OPD_Tahun.xls (contoh: Dinas_Kesehatan_2025.xls)
                    </p>

                    <div class="border-2 border-dashed border-red-400 bg-red-50 rounded-lg p-6 text-center mb-6">
                        <input 
                            type="file" 
                            id="upload" 
                            name="file"
                            accept=".xls,.xlsx" 
                            class="hidden" 
                            @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''"
                            required
                        >
                        <label for="upload" class="cursor-pointer flex flex-col items-center">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-red-500 mb-2"></i>
                            <p class="text-red-600 font-semibold">Cari File Untuk Diunggah</p>
                        </label>
                        <p x-show="fileName" x-text="'File dipilih: ' + fileName" class="mt-3 text-sm text-gray-700 font-medium"></p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 rounded bg-[#FE482B] text-white hover:bg-white hover:outline-1 hover:outline hover:text-[#FE482B] cursor-pointer transition duration-200">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Illustration -->
        <div class="hidden md:flex md:col-span-2 items-end justify-center">
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
    </div>
</x-navbar>