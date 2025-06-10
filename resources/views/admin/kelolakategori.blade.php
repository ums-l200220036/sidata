@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<x-navbar>
<div class="min-h-screen flex items-start justify-center py-10 px-4"
    x-data="{
        showModal: {{ $errors->any() && old('is_edit') != 'true' ? 'true' : 'false' }},
        showEditModal: {{ $errors->any() && old('is_edit') == 'true' ? 'true' : 'false' }},
        
        opds: JSON.parse('{{ addslashes(json_encode($opds)) }}'),
        
        newIndikatorData: {
            nama_indikator: '{{ old('nama_indikator', '') }}',
            opd_id: '{{ old('opd_id', '') }}',
        },
        editIndikator: { 
            id: {{ old('edit_user_id', 'null') }}, 
            name_indikator: '{{ old('name', '') }}',
            opd_id: '{{ old('opd_id', '') }}',
        },
        openEditModal(indikator) {
            this.editIndikator = { ...indikator };
            this.editIndikator.opd_id = indikator.opd_id ?? '';
            this.showEditModal = true;
        }
    }"
>
    <div class="w-full max-w-5xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Kelola Indikator</h2>
            <button
                @click="showModal = true; newIndikatorData.nama_indikator = ''; newIndikatorData.opd_id = '';"
                class="inline-flex items-center bg-[#FE482B] text-white text-sm font-semibold px-4 py-2 rounded-lg shadow hover:bg-[#e03e22] transition">
                <i class="fas fa-plus mr-2"></i> Tambah Indikator
            </button>
        </div>

        {{-- Notifikasi Sukses/Error --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>Terdapat kesalahan pada input:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                {{-- Hidden input untuk re-open modal jika ada error validasi --}}
                {{-- Penting: is_edit dan edit_indikator_id/nama_indikator/opd_id harus konsisten dengan old() --}}
                @if(old('is_edit') === 'true')
                    <input type="hidden" x-init="showEditModal = true" name="is_edit_error_flag" value="true">
                    <input type="hidden" x-init="
                        editIndikator.id = '{{ old('edit_indikator_id') ?? 'null' }}';
                        editIndikator.nama_indikator = '{{ addslashes(old('nama_indikator') ?? '') }}';
                        editIndikator.opd_id = '{{ old('opd_id') ?? '' }}';
                    " name="edit_indikator_data_flag" value="true">
                @elseif
                    <input type="hidden" x-init="showModal = true" name="is_add_error_flag" value="true">
                    <input type="hidden" x-init="
                        newIndikatorData.nama_indikator = '{{ addslashes(old('nama_indikator') ?? '') }}';
                        newIndikatorData.opd_id = '{{ old('opd_id') ?? '' }}';
                    " name="add_indikator_data_flag" value="true">
                @endif
            </div>
        @endif


        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-[#FE482B] text-white text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Indikator</th>
                        <th class="px-4 py-3 text-left">OPD</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-800">
                    @forelse ($kategoris as $index => $indikator)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $indikator->nama_indikator }}</td>
                        <td class="px-4 py-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $indikator->opd->nama_opd ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap space-x-2">
                            <button
                                @click="openEditModal({
                                    id: {{ $indikator->id }},
                                    nama_indikator: '{{ addslashes($indikator->nama_indikator) }}',
                                    opd_id: '{{ $indikator->opd_id }}'
                                })"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <form action="{{ route('indikator.destroy', $indikator->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus indikator ini?')" class="text-red-600 hover:text-red-800 text-sm font-medium inline-flex items-center">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada data indikator.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah Indikator --}}
        <div
            x-show="showModal"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4"
            x-transition
        >
            <div
                @click.away="showModal = false"
                class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 space-y-4 overflow-y-auto max-h-[90vh]"
                x-transition
            >
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Indikator</h3>
                    <button @click="showModal = false" class="text-gray-600 hover:text-gray-800 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('indikator.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="is_edit" value="false">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">Nama Indikator</label>
                        <input type="text" name="nama_indikator" x-model="newIndikatorData.nama_indikator" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('nama_indikator') border-red-500 @enderror">
                        @error('nama_indikator')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">Label Indikator</label>
                        <input type="text" name="label_indikator" x-model="newIndikatorData.label_indikator" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('label_indikator') border-red-500 @enderror">
                        @error('label_indikator')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">OPD</label>
                        <select name="opd_id" x-model="newIndikatorData.opd_id" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('opd_id') border-red-500 @enderror">
                            <option value="" disabled>-- Pilih OPD --</option>
                            <template x-for="opd in opds" :key="opd.id">
                                <option :value="opd.id" x-text="opd.nama_opd"></option>
                            </template>
                        </select>
                        @error('opd_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#FE482B] text-white rounded hover:bg-[#e03e22]">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Indikator --}}
        <div
            x-show="showEditModal"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4"
            x-transition
        >
            <div
                @click.away="showEditModal = false"
                class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 space-y-4 overflow-y-auto max-h-[90vh]"
                x-transition
            >
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Indikator</h3>
                    <button @click="showEditModal = false" class="text-gray-600 hover:text-gray-800 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form :action="`/indikator/${editIndikator.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="is_edit" value="true">
                    <input type="hidden" name="edit_indikator_id" :value="editIndikator.id">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">Nama Indikator</label>
                        <input type="text" name="nama_indikator" x-model="editIndikator.nama_indikator" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('nama_indikator') border-red-500 @enderror">
                        @error('nama_indikator')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">Nama Indikator</label>
                        <input type="text" name="label_indikator" x-model="editIndikator.label_indikator" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('label_indikator') border-red-500 @enderror">
                        @error('label_indikator')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 font-medium mb-1">OPD</label>
                        <select name="opd_id" x-model="editIndikator.opd_id" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('opd_id') border-red-500 @enderror">
                            <option value="" disabled>-- Pilih OPD --</option>
                            <template x-for="opd in opds" :key="opd.id">
                                <option :value="opd.id" x-text="opd.nama_opd"></option>
                            </template>
                        </select>
                        @error('opd_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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