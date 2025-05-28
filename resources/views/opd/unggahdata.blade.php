<x-usernav>
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-3 bg-cover bg-no-repeat bg-right" style="background-image: url('{{ asset('images/jumbotronimage.png') }}')">
        <!-- Form Section -->
        <div class="pl-24 bg-white shadow-2xl shadow-gray-400/50">
            <div class="md:col-span-1 flex flex-col justify-center bg-white bg-opacity-90 p-10 pl-0">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">
                    Unggah File Excel Data Sektoral / Statistik OPD
                </h1>

                <!-- Kategori Dropdown dengan style Tailwind -->
                <label for="kategori" class="block mb-2 font-semibold text-gray-700">
                    Kategori: <span class="text-red-500">*</span>
                </label>
                <div class="relative w-full mb-6">
                    <select id="kategori" name="kategori"
                        class="w-full appearance-none px-4 py-3 border border-[#FE482B] rounded-xl shadow-sm text-gray-700 font-medium bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                        <option value="" disabled selected hidden>Pilih Kategori</option>
                        <option value="kesehatan">Kesehatan</option>
                        <option value="pendidikan">Pendidikan</option>
                        <option value="infrastruktur">Infrastruktur</option>
                    </select>
                    <!-- Custom Dropdown Icon -->
                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                

                <p class="text-sm text-gray-600 mb-4">
                    Sebelum mengunggah data, harap pilih kategori terlebih dahulu, lalu unduh template Excel berikut sebagai panduan pengisian data sektoral atau statistik OPD Kota Surakarta:
                </p>

                <a href="#"
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r bg-[#FE482B] text-white hover:bg-white hover:border hover:border-[#FE482B] hover:text-[#FE482B] rounded-lg shadow-lg font-semibold mb-6 transition duration-300"
                >
                    <i class="fas fa-download"></i> Unduh Template Excel
                </a>

                <!-- Upload Section with Alpine.js -->
                <label for="upload" class="block mb-2 font-semibold text-gray-700">
                    Unggah File Excel: <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-2">
                    Setelah data terisi sesuai format, silahkan unggah file Excel Anda di bawah ini.<br>
                    Format file: .xlsx atau .xls<br>
                    Penamaan file: Nama_OPD_Tahun.xls (contoh: Dinas_Kesehatan_2025.xls)
                </p>

                <div x-data="{ fileName: '' }" class="border-2 border-dashed border-red-400 bg-red-50 rounded-lg p-6 text-center mb-6">
                    <input 
                        type="file" 
                        id="upload" 
                        accept=".xls,.xlsx" 
                        class="hidden" 
                        @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''"
                    >
                    <label for="upload" class="cursor-pointer flex flex-col items-center">
                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-red-500 mb-2"></i>
                        <p class="text-red-600 font-semibold">Cari File Untuk Diunggah</p>
                    </label>
                    <p x-show="fileName" x-text="'File dipilih: ' + fileName" class="mt-3 text-sm text-gray-700 font-medium"></p>
                </div>

                <button class="w-full py-3 rounded bg-[#FE482B] text-white hover:bg-white hover:border hover:border-[#FE482B] hover:text-[#FE482B] cursor-pointer transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim
                </button>
            </div>
        </div>

        <!-- Right Illustration -->
        <div class="hidden md:flex md:col-span-2 items-end justify-center">
            <!-- Kosong karena background sudah ada -->
        </div>
    </div>
</x-usernav>
