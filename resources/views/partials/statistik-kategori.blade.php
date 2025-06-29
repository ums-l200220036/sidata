@php
    // Role dikirim dari controller atau main blade
    $role = $role ?? 'guest';
@endphp

<form class="w-full mx-auto gap-6 px-28" x-data="{ selected: '' }">
    @if ($role === 'admin')
        {{-- Admin bisa lihat manajemen OPD, kategori, dan data --}}
        <label
            :class="selected === 'kelola_pengguna' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
            class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
            for="kelola_pengguna"
        >
            <input
                type="radio"
                id="manajemen_opd"
                name="kategori"
                value="kelola_pengguna"
                class="hidden"
                x-model="selected"
            />
            <i class="fa-solid fa-building text-4xl mb-3"></i>
            Kelola Pengguna
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
            Data Seluruh Kota
        </label>
    @elseif ($role === 'opd')
<div class="grid w-full bg-white grid-cols-1 md:grid-cols-4 gap-4">
    @foreach ($indikators as $indikator)
    <a
        href="{{ route($indikator->route_name, ['indikatorId' => $indikator->id]) }}"
        :class="selected === '' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
        class="cursor-pointer rounded-md p-3 shadow-sm flex flex-col justify-center items-center font-medium text-sm transition duration-300 ease-in-out"
    >
        {{ $indikator->nama_indikator }}
    </a>
    @endforeach
</div>
    @elseif ($role === 'kecamatan')
        <div class="grid w-full bg-white grid-cols-1 md:grid-cols-4 gap-4">
            @forelse ($indikators as $indikator)
                <a
                    href="{{ route($indikator->route_name, ['indikatorId' => $indikator->id]) }}"
                    :class="selected === '' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
                    class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
                >
                    {{ $indikator->nama_indikator }}
                </a>
            @empty
                <p class="md:col-span-4 text-center text-gray-600">Tidak ada indikator yang tersedia.</p>
            @endforelse
        </div>

    @elseif ($role === 'kelurahan')
        <div class="grid w-full bg-white grid-cols-1 md:grid-cols-4 gap-4">
            @forelse ($indikators as $indikator)
                <a
                    href="{{ route($indikator->route_name, ['indikatorId' => $indikator->id]) }}"
                    :class="selected === '' ? 'bg-[#FE482B] text-white' : 'bg-white text-[#FE482B] border border-[#FE482B]'"
                    class="cursor-pointer rounded-lg p-6 shadow-md flex flex-col justify-center items-center font-semibold transition duration-300 ease-in-out"
                >
                    {{ $indikator->nama_indikator }}
                </a>
            @empty
                <p class="md:col-span-4 text-center text-gray-600">Tidak ada indikator yang tersedia.</p>
            @endforelse
        </div>
    @else
        {{-- Guest / lain-lain --}}
        <p class="text-gray-500">Anda tidak memiliki akses data.</p>
    @endif
</form>