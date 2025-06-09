@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<x-navbar>
<div class="min-h-screen flex items-start justify-center py-10 px-4"
    x-data="{
        showModal: false,
        showEditModal: false,
        editKategori: { id: null, nama: '', opd: '' },
        openEditModal(id, nama, opd) {
            this.editKategori = { id, nama, opd };
            this.showEditModal = true;
        }
    }"
>
    <div class="w-full max-w-5xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Kelola Kategori</h2>
            <button
                @click="showModal = true"
                class="inline-flex items-center bg-[#FE482B] text-white text-sm font-semibold px-4 py-2 rounded-lg shadow hover:bg-[#e03e22] transition">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-[#FE482B] text-white text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">OPD</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-800">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2">1</td>
                        <td class="px-4 py-2">Berdasar Kelamin</td>
                        <td class="px-4 py-2">
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Dinas Kesehatan
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap space-x-2">
                            <button
                                @click="openEditModal(1, 'Berdasar Kelamin', 'Dinas Kesehatan')"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button onclick="return confirm('Yakin hapus?')" class="text-red-600 hover:text-red-800 text-sm font-medium inline-flex items-center">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah Kategori --}}
        <div
            x-show="showModal"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
            x-transition
        >
            <div
                @click.away="showModal = false"
                class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 space-y-4"
                x-transition
            >
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Kategori</h3>
                    <button @click="showModal = false" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">Nama Kategori</label>
                        <input type="text" name="kategori" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FE482B]">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">OPD</label>
                        <select name="opd" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FE482B]">
                            <option value="" disabled selected>-- Pilih OPD --</option>
                            <option value="Kecamatan">Kecamatan</option>
                            <option value="Kelurahan">Kelurahan</option>
                            <option value="Dinas Pendidikan">Dinas Pendidikan</option>
                            <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                            <option value="Dinas Sosial">Dinas Sosial</option>
                            <option value="Satuan Polisi Pamong Praja">Satuan Polisi Pamong Praja</option>
                            <option value="Badan Penanggulangan Bencana Daerah">Badan Penanggulangan Bencana Daerah</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#FE482B] text-white rounded hover:bg-[#e03e22]">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Kategori --}}
        <div
            x-show="showEditModal"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
            x-transition
        >
            <div
                @click.away="showEditModal = false"
                class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 space-y-4"
                x-transition
            >
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Kategori</h3>
                    <button @click="showEditModal = false" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="editKategori.id">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">Nama Kategori</label>
                        <input type="text" name="kategori" x-model="editKategori.nama" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FE482B]">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">OPD</label>
                        <select name="opd" x-model="editKategori.opd" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FE482B]">
                            <option value="" disabled>-- Pilih OPD --</option>
                            <option value="Kecamatan">Kecamatan</option>
                            <option value="Kelurahan">Kelurahan</option>
                            <option value="Dinas Pendidikan">Dinas Pendidikan</option>
                            <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                            <option value="Dinas Sosial">Dinas Sosial</option>
                            <option value="Satuan Polisi Pamong Praja">Satuan Polisi Pamong Praja</option>
                            <option value="Badan Penanggulangan Bencana Daerah">Badan Penanggulangan Bencana Daerah</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#FE482B] text-white rounded hover:bg-[#e03e22]">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-navbar>
@endsection
