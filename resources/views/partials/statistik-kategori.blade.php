@php
    // Role dikirim dari controller atau main blade
    $role = $role ?? 'guest';
@endphp

<form class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 px-4" x-data="{ selected: '' }">
    @if ($role === 'admin')
        {{-- Admin bisa lihat manajemen OPD, kategori, dan data --}}
        <label
            :class="selected === 'manajemen_opd' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="manajemen_opd"
        >
            <input
                type="radio"
                id="manajemen_opd"
                name="kategori"
                value="manajemen_opd"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-building text-4xl mb-3"></i>
            Manajemen OPD
        </label>

        <label
            :class="selected === 'kategori_data' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="kategori_data"
        >
            <input
                type="radio"
                id="kategori_data"
                name="kategori"
                value="kategori_data"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-list text-4xl mb-3"></i>
            Kategori Data
        </label>

        <label
            :class="selected === 'data_kota' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="data_kota"
        >
            <input
                type="radio"
                id="data_kota"
                name="kategori"
                value="data_kota"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-city text-4xl mb-3"></i>
            Data Seluruh Kota
        </label>
    @elseif ($role === 'opd')
        {{-- OPD hanya bisa akses data sesuai opd --}}
        <label
            :class="selected === 'data_opd' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="data_opd"
        >
            <input
                type="radio"
                id="data_opd"
                name="kategori"
                value="data_opd"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-database text-4xl mb-3"></i>
            Data OPD
        </label>
    @elseif ($role === 'kelurahan')
        {{-- Kelurahan hanya data kelurahan --}}
        <label
            :class="selected === 'data_kelurahan' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="data_kelurahan"
        >
            <input
                type="radio"
                id="data_kelurahan"
                name="kategori"
                value="data_kelurahan"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-house text-4xl mb-3"></i>
            Data Kelurahan
        </label>
    @else
        {{-- Guest / lain-lain --}}
        <p class="text-gray-500">Anda tidak memiliki akses data.</p>
    @endif
</form>