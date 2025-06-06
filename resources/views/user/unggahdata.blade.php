<x-navbar>
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="min-h-screen grid grid-cols-1 md:grid-cols-3 bg-cover bg-no-repeat bg-right">
            <!-- Form Section -->
            <div class="pl-24 bg-white shadow-2xl shadow-gray-400/50">
                <div class="md:col-span-1 flex flex-col justify-center bg-white bg-opacity-90 p-10 pl-0">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">
                        Unggah File Excel Data Sektoral / Statistik OPD
                    </h1>
    
                    <!-- Indikator -->
                    <label for="kategori" class="block mb-2 font-semibold text-gray-700">
                        Indikator: <span class="text-red-500">*</span>
                    </label>
                    <div class="relative w-full mb-6">
                        <select id="kategori" name="indikator_id"
                            class="w-full px-4 py-3 border border-[#FE482B] rounded-xl shadow-sm text-gray-700 font-medium bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            <option value="" disabled selected hidden>Pilih Indikator</option>
                            @foreach($indikatorList as $indikator)
                                <option value="{{ $indikator->id }}">{{ $indikator->nama_indikator }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Template Link -->
                    <p class="text-sm text-gray-600 mb-4">
                        Unduh template Excel sesuai kategori.
                    </p>
                    <a href="{{ asset('template/template_excel.xlsx') }}"
                       class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r bg-[#FE482B] text-white hover:outline-1 hover:outline hover:bg-white hover:text-[#FE482B] rounded-lg shadow-lg font-semibold mb-6 transition duration-300"
                    >
                        <i class="fas fa-download"></i> Unduh Template Excel
                    </a>
    
                    <!-- Upload -->
                    <label for="upload" class="block mb-2 font-semibold text-gray-700">
                        Unggah File Excel: <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-600 mb-2">
                        Format: .xls/.xlsx<br>
                        Penamaan: Nama_OPD_Tahun.xls
                    </p>
    
                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-red-400 bg-red-50 rounded-lg p-6 text-center mb-6">
                        <input 
                            type="file" 
                            id="upload" 
                            name="file"
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
    
                    <!-- Submit -->
                    <button class="w-full py-3 rounded bg-[#FE482B] text-white hover:bg-white hover:outline-1 hover:outline hover:text-[#FE482B] transition duration-200">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim
                    </button>
    
                    @if (session('success'))
                        <p class="mt-4 text-green-600 font-semibold">{{ session('success') }}</p>
                    @endif
    
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
    
                    @error('file_excel')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
    
                </div>
            </div>
    
            <!-- Ilustrasi -->
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
    </form>
    </x-navbar>
    