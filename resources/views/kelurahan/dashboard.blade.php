<x-navbar>
    <div class="relative text-center py-16 bg-white">
        <div class="max-w-4xl mx-auto">
            <p class="text-[#FE482B] text-base font-medium">Dinas Kesehatan</p>
            <h1 class="text-4xl font-bold mt-1">Sugeng Rawuh</h1>
            <div class="flex justify-center items-center mt-2 gap-4">
                <p class="text-black text-base leading-relaxed"><i class="fa-solid fa-calendar-days mr-1"></i> Jum'at 16 Mei 2025</p>
                <p class="text-black text-base leading-relaxed"><i class="fa-solid fa-clock mr-2"></i> 23:36:25 WIB</p>
            </div>
        </div>

        <img src="{{ asset('images/jumbotronimage.png') }}"
             alt="Jumbotron Image"
             class="w-full h-auto -mt-20" />
    </div>

    <section class="py-5 bg-white text-center">
    <p class="text-[#FE482B] text-sm font-medium mb-4">Statistik Data</p>
    <h2 class="text-3xl font-bold mb-10">Kategori Data</h2>

    <form class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 px-4" x-data="{ selected: '' }">
        <!-- Pilihan Kategori sebagai radio cards -->
        <label
            :class="selected === 'tenaga_kesehatan' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="tenaga_kesehatan"
        >
            <input
                type="radio"
                id="tenaga_kesehatan"
                name="kategori"
                value="tenaga_kesehatan"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-user-doctor text-4xl mb-3"></i>
            Data Tenaga Kesehatan
        </label>

        <label
            :class="selected === 'jumlah_rumah_sakit' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="jumlah_rumah_sakit"
        >
            <input
                type="radio"
                id="jumlah_rumah_sakit"
                name="kategori"
                value="jumlah_rumah_sakit"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-hospital text-4xl mb-3"></i>
            Data Jumlah Rumah Sakit
        </label>

        <label
            :class="selected === 'jumlah_kasus' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="jumlah_kasus"
        >
            <input
                type="radio"
                id="jumlah_kasus"
                name="kategori"
                value="jumlah_kasus"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-virus-covid text-4xl mb-3"></i>
            Data Jumlah Kasus
        </label>
    </form>
</section>

</x-navbar>