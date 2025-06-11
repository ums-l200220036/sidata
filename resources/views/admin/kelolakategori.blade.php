@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<x-navbar>
    {{-- Main container for the entire page content to constrain its width --}}
    <div class="container mx-auto px-4 md:px-8 lg:px-16 pb-10 max-w-full"
        x-data="{
            showModal: {{ $errors->any() && old('is_edit') != 'true' ? 'true' : 'false' }},
            showEditModal: {{ $errors->any() && old('is_edit') == 'true' ? 'true' : 'false' }},

            opds: JSON.parse('{{ addslashes(json_encode($opds)) }}'),

            newIndikatorData: {
                nama_indikator: '{{ old('nama_indikator', '') }}',
                opd_id: '{{ old('opd_id', '') }}',
                dimensi_label: '{{ old('dimensi_label', '') }}',
            },
            editIndikator: {
                id: {{ old('edit_indikator_id', 'null') }},
                nama_indikator: '{{ old('nama_indikator', '') }}',
                opd_id: '{{ old('opd_id', '') }}',
                dimensi_label: '{{ old('dimensi_label', '') }}',
            },
            openEditModal(indikator) {
                this.editIndikator = {
                    id: indikator.id,
                    nama_indikator: indikator.nama_indikator,
                    opd_id: indikator.opd_id ?? '',
                    dimensi_label: indikator.dimensi_label ?? '',
                };
                this.showEditModal = true;
            }
        }"
    >
        <div class="bg-white p-6 md:p-8 lg:p-10 h-screen">
            <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-cubes text-[#FE482B]"></i> Kelola Indikator
                </h2>
                <button
                    @click="showModal = true; newIndikatorData.nama_indikator = ''; newIndikatorData.opd_id = ''; newIndikatorData.dimensi_label = '';"
                    class="flex items-center gap-2 bg-[#FE482B] hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FE482B]">
                    <i class="fas fa-plus text-lg"></i>
                    <span class="text-lg font-medium">Tambah Indikator</span>
                </button>
            </div>

            {{-- Notifikasi Sukses/Error --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-semibold mb-2">Terdapat kesalahan pada input:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- Hidden input untuk re-open modal jika ada error validasi --}}
                @if(old('is_edit') === 'true')
                    <input type="hidden" x-init="showEditModal = true;
                        editIndikator.id = '{{ old('edit_indikator_id') ?? 'null' }}';
                        editIndikator.nama_indikator = '{{ addslashes(old('nama_indikator') ?? '') }}';
                        editIndikator.opd_id = '{{ old('opd_id') ?? '' }}';
                        editIndikator.dimensi_label = '{{ addslashes(old('dimensi_label') ?? '') }}';"
                        name="is_edit_error_flag" value="true">
                @else
                    <input type="hidden" x-init="showModal = true;
                        newIndikatorData.nama_indikator = '{{ addslashes(old('nama_indikator') ?? '') }}';
                        newIndikatorData.opd_id = '{{ old('opd_id') ?? '' }}';
                        newIndikatorData.dimensi_label = '{{ addslashes(old('dimensi_label') ?? '') }}';"
                        name="is_add_error_flag" value="true">
                @endif
            @endif

            {{-- Table --}}
            <div class="rounded-lg shadow-inner border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-[#FE482B]">
                        <tr>
                            <th class="w-12 px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                            <th class="w-1/3 px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Indikator</th>
                            <th class="w-1/4 px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Label Dimensi</th>
                            <th class="w-1/5 px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">OPD</th>
                            <th class="w-1/6 px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-800">
                        @forelse ($kategoris as $index => $indikator)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-3 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-3 py-4 whitespace-nowrap font-medium text-gray-900 overflow-hidden text-ellipsis">{{ $indikator->nama_indikator }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-gray-700 overflow-hidden text-ellipsis">{{ $indikator->dimensi_label ?? '-' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    {{ $indikator->opd->nama_opd ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button
                                    @click="openEditModal({
                                        id: {{ $indikator->id }},
                                        nama_indikator: '{{ addslashes($indikator->nama_indikator) }}',
                                        opd_id: '{{ $indikator->opd_id }}',
                                        dimensi_label: '{{ addslashes($indikator->dimensi_label ?? '') }}'
                                    })"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out mr-2">
                                    <i class="fas fa-edit text-base mr-1"></i> Edit
                                </button>
                                <form action="{{ route('indikator.destroy', $indikator->id) }}" method="POST" class="inline" @submit.prevent="if(confirm('Apakah Anda yakin ingin menghapus indikator ini?')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center text-red-600 hover:text-red-800 transition duration-150 ease-in-out">
                                        <i class="fas fa-trash-alt text-base mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500 text-base">Tidak ada data indikator yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- End Table --}}
        </div>

        {{-- Modal Tambah Indikator --}}
        <div x-show="showModal" x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div @click.away="showModal = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 md:p-8 relative max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out"
                :class="showModal ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
                <button @click="showModal = false"
                        class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-2xl font-bold text-[#FE482B] mb-6 flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i> Tambah Indikator Baru
                </h3>
                <form action="{{ route('indikator.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="is_edit" value="false">
                    <div class="mb-4">
                        <label for="nama_indikator_add" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Indikator</label>
                        <input type="text" id="nama_indikator_add" name="nama_indikator" x-model="newIndikatorData.nama_indikator" required
                            class="w-full px-4 py-2.5 rounded-lg focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm transition @error('nama_indikator') border-red-500 @enderror">
                        @error('nama_indikator')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="dimensi_label_add" class="block text-sm font-medium text-gray-700 mb-1.5">Label Dimensi</label>
                        <input type="text" id="dimensi_label_add" name="dimensi_label" x-model="newIndikatorData.dimensi_label" required
                            class="w-full px-4 py-2.5 rounded-lg focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm transition @error('dimensi_label') border-red-500 @enderror">
                        @error('dimensi_label')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="opd_id_add" class="block text-sm font-medium text-gray-700 mb-1.5">OPD</label>
                        <select id="opd_id_add" name="opd_id" x-model="newIndikatorData.opd_id" required
                            class="w-full px-4 py-2.5 rounded-lg focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm transition @error('opd_id') border-red-500 @enderror">
                            <option value="" disabled>-- Pilih OPD --</option>
                            <template x-for="opd in opds" :key="opd.id">
                                <option :value="opd.id" x-text="opd.nama_opd"></option>
                            </template>
                        </select>
                        @error('opd_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showModal = false"
                            class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#FE482B] text-white rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FE482B]">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Indikator --}}
        <div x-show="showEditModal" x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div @click.away="showEditModal = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 md:p-8 relative max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out"
                :class="showEditModal ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
                <button @click="showEditModal = false"
                        class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-2xl font-bold text-[#FE482B] mb-6 flex items-center gap-3">
                    <i class="fas fa-edit"></i> Edit Indikator
                </h3>
                <form :action="`/indikator/${editIndikator.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="is_edit" value="true">
                    <input type="hidden" name="edit_indikator_id" :value="editIndikator.id">
                    <div class="mb-4">
                        <label for="nama_indikator_edit" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Indikator</label>
                        <input type="text" id="nama_indikator_edit" name="nama_indikator" x-model="editIndikator.nama_indikator" required
                            class="w-full px-4 py-2.5 rounded-lg focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm transition @error('nama_indikator') border-red-500 @enderror">
                        @error('nama_indikator')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="dimensi_label_edit" class="block text-sm font-medium text-gray-700 mb-1.5">Label Dimensi</label>
                        <input type="text" id="dimensi_label_edit" name="dimensi_label" x-model="editIndikator.dimensi_label" required
                            class="w-full px-4 py-2.5 rounded-lg focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm transition @error('dimensi_label') border-red-500 @enderror">
                        @error('dimensi_label')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="opd_id_edit" class="block text-sm font-medium text-gray-700 mb-1.5">OPD</label>
                        <select id="opd_id_edit" name="opd_id" x-model="editIndikator.opd_id" required
                            class="w-full px-4 py-2.5 rounded-lg focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm transition @error('opd_id') border-red-500 @enderror">
                            <option value="" disabled>-- Pilih OPD --</option>
                            <template x-for="opd in opds" :key="opd.id">
                                <option :value="opd.id" x-text="opd.nama_opd"></option>
                            </template>
                        </select>
                        @error('opd_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showEditModal = false"
                            class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#FE482B] text-white rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FE482B]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-navbar>
@endsection