@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<x-navbar>
    <div class="bg-white px-16 rounded-xl" x-data="
        // Inisialisasi properti data utama di sini
        {
            showModal: {{ $errors->any() && old('is_edit') != 'true' ? 'true' : 'false' }},
            showEditModal: {{ $errors->any() && old('is_edit') == 'true' ? 'true' : 'false' }},

            searchTerm: '', // Variabel untuk menyimpan input pencarian
            usersData: JSON.parse('{{ addslashes(json_encode($users)) }}'), // Data pengguna asli dari controller

            // Menggunakan JSON.parse untuk memastikan data array/objek dari Blade aman
            allOpds: JSON.parse('{{ addslashes(json_encode($allOpds)) }}'),
            allKecamatans: JSON.parse('{{ addslashes(json_encode($kecamatans)) }}'),
            allKelurahans: JSON.parse('{{ addslashes(json_encode($kelurahans)) }}'),

            // Properti untuk warna role, dipindahkan ke Alpine.js data
            roleColors: {
                'admin': 'bg-green-100 text-green-700',
                'opd': 'bg-blue-100 text-blue-700',
                'kecamatan': 'bg-purple-100 text-purple-700',
                'kelurahan': 'bg-yellow-100 text-yellow-700',
            },

            newUserData: {
                name: '{{ old('name', '') }}',
                email: '{{ old('email', '') }}',
                role: '{{ old('role', '') }}',
                opd_id: '{{ old('opd_id', '') }}',
                wilayah_id: '{{ old('wilayah_id', '') }}',
                password: '',
                password_confirmation: ''
            },
            selectedUser: {
                id: {{ old('edit_user_id', 'null') }},
                name: '{{ old('name', '') }}',
                email: '{{ old('email', '') }}',
                role: '{{ old('role', '') }}',
                opd_id: '{{ old('opd_id', '') }}',
                wilayah_id: '{{ old('wilayah_id', '') }}',
                password: '',
                password_confirmation: ''
            },

            filteredOpdsForSelect: [],
            filteredWilayahsForSelect: [],

            // Panggil fungsi inisialisasi saat Alpine siap
            init() {
                // Jika modal Tambah perlu ditampilkan (misal karena error validasi)
                if (this.showModal) {
                    this.filterDropdowns(this.newUserData.role, false);
                }
                // Jika modal Edit perlu ditampilkan (misal karena error validasi)
                if (this.showEditModal) {
                    this.filterDropdowns(this.selectedUser.role, true);
                }
            },

            // Definisikan fungsi-fungsi Alpine di sini (di luar init)
            filterDropdowns(selectedRole, isEdit = false) {
                let dataToUpdate = isEdit ? this.selectedUser : this.newUserData;

                if (dataToUpdate.role !== selectedRole) {
                    dataToUpdate.opd_id = '';
                    dataToUpdate.wilayah_id = '';
                }

                dataToUpdate.role = selectedRole;

                this.filteredOpdsForSelect = [];
                this.filteredWilayahsForSelect = [];

                if (selectedRole === 'opd') {
                    this.filteredOpdsForSelect = this.allOpds.filter(opd => ![
                        'Kecamatan Banjarsari', 'Kecamatan Jebres', 'Kecamatan Laweyan',
                        'Kecamatan Pasarkliwon', 'Kecamatan Serengan'
                    ].includes(opd.nama_opd));
                } else if (selectedRole === 'kecamatan') {
                    this.filteredWilayahsForSelect = this.allKecamatans;
                } else if (selectedRole === 'kelurahan') {
                    this.filteredWilayahsForSelect = this.allKelurahans;
                }
            },

            openEditModal(user) {
                this.selectedUser = { ...user };
                this.selectedUser.opd_id = user.opd_id ?? '';
                this.selectedUser.wilayah_id = user.wilayah_id ?? '';

                this.filterDropdowns(this.selectedUser.role, true);
                this.showEditModal = true;
            },

            // Computed property untuk filter pencarian
            get filteredUsers() {
                if (this.searchTerm === '') {
                    return this.usersData;
                }
                const lowerCaseSearchTerm = this.searchTerm.toLowerCase();
                return this.usersData.filter(user => {
                    return user.name.toLowerCase().includes(lowerCaseSearchTerm);
                });
            }
        }
    ">

        <div class="bg-white rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-users text-[#FE482B]"></i>Kelola Pengguna
                </h2>
            </div>
            <div class="flex items-center justify-between mb-6">
                 {{-- Input Pencarian --}}
                <div class="w-1/3">
                    <input type="text" x-model="searchTerm" placeholder="Cari pengguna berdasarkan nama..."
                        class=" w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                </div>
                <button @click="showModal = true; newUserData.role = ''; newUserData.opd_id = ''; newUserData.wilayah_id = ''; filterDropdowns('');"
                    class="flex items-center gap-2 bg-[#FE482B] hover:bg-orange-600 text-white px-4 py-2 rounded-md shadow transition">
                    <i class="fas fa-user-plus"></i>
                    Tambah Pengguna
                </button>
            </div>

           
            {{-- End Input Pencarian --}}

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
                </div>
            @endif


            <div class="overflow-x-auto shadow-inner">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#FE482B] text-white text-sm uppercase">
                        <tr class="textcenter">
                            <th class="px-6 py-3 text-left tracking-wider">No</th>
                            <th class="px-6 py-3 text-left tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left tracking-wider">Afiliasi</th> {{-- Menggunakan Accessor --}}
                            <th class="px-6 py-3 text-left tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                        {{-- Menggunakan x-for untuk iterasi data yang sudah difilter oleh Alpine.js --}}
                        <template x-for="(user, index) in filteredUsers" :key="user.id">
                        <tr class="hover:bg-gray-100 transition">
                            <td class="px-6 py-4" x-text="index + 1"></td>
                            <td class="px-6 py-4 font-medium" x-text="user.name"></td>
                            <td class="px-6 py-4" x-text="user.email"></td>
                            <td class="px-6 py-4" x-text="user.affiliation_name"></td>
                            <td class="px-6 py-4">
                                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full"
                                    :class="roleColors[user.role] || 'bg-gray-100 text-gray-700'"
                                    x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)">
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                <button
                                    @click="openEditModal(user)"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <form method="POST" :action="`/users/${user.id}`" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                        class="inline-flex items-center text-red-600 hover:text-red-800 transition">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        </template>
                        {{-- Pesan jika tidak ada hasil pencarian --}}
                        <template x-if="filteredUsers.length === 0">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna yang ditemukan.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah Pengguna --}}
        <div x-show="showModal" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.away="showModal = false"
                class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative overflow-y-auto max-h-[90vh]">
                <button @click="showModal = false"
                        class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Tambah Pengguna
                </h3>
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    {{-- Hidden input untuk menandai bahwa ini dari modal tambah --}}
                    <input type="hidden" name="is_edit" value="false">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" x-model="newUserData.name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="newUserData.email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" x-model="newUserData.role" @change="filterDropdowns(newUserData.role, false)" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('role') border-red-500 @enderror">
                            <option value="" disabled>-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="opd" {{ old('role') == 'opd' ? 'selected' : '' }}>OPD</option>
                            <option value="kecamatan" {{ old('role') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                            <option value="kelurahan" {{ old('role') == 'kelurahan' ? 'selected' : '' }}>Kelurahan</option>
                        </select>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4" x-show="newUserData.role === 'opd'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organisasi Perangkat Daerah (OPD)</label>
                        <select name="opd_id" x-model="newUserData.opd_id" :required="newUserData.role === 'opd'"
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('opd_id') border-red-500 @enderror">
                            <option value="">-- Pilih OPD --</option>
                            <template x-for="opd in filteredOpdsForSelect" :key="opd.id">
                                <option :value="opd.id" x-text="opd.nama_opd" :selected="opd.id == {{ old('opd_id', 'null') }}"></option>
                            </template>
                        </select>
                        @error('opd_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4" x-show="newUserData.role === 'kecamatan' || newUserData.role === 'kelurahan'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                        <select name="wilayah_id" x-model="newUserData.wilayah_id" :required="newUserData.role === 'kecamatan' || newUserData.role === 'kelurahan'"
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('wilayah_id') border-red-500 @enderror">
                            <option value="">-- Pilih Wilayah --</option>
                            <template x-for="wilayah in filteredWilayahsForSelect" :key="wilayah.id">
                                <option :value="wilayah.id" x-text="wilayah.kelurahan ? wilayah.kelurahan + ' (' + wilayah.kecamatan + ')' : wilayah.kecamatan" :selected="wilayah.id == {{ old('wilayah_id', 'null') }}"></option>
                            </template>
                        </select>
                        @error('wilayah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4" x-data="{ show: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" x-model="newUserData.password"
                                class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10 @error('password') border-red-500 @enderror" required>
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6" x-data="{ showConfirm: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="newUserData.password_confirmation"
                                class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10 @error('password_confirmation') border-red-500 @enderror" required>
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password_confirmation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#FE482B] text-white rounded hover:bg-orange-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Pengguna --}}
        <div x-show="showEditModal" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.away="showEditModal = false"
                class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative overflow-y-auto max-h-[90vh]">
                <button @click="showEditModal = false"
                        class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Pengguna
                </h3>
                <form :action="`/users/${selectedUser.id}`" method="POST" x-ref="editForm">
                    @csrf
                    @method('PUT')
                    {{-- Hidden input untuk menandai bahwa ini dari modal edit --}}
                    <input type="hidden" name="is_edit" value="true">
                    <input type="hidden" name="edit_user_id" :value="selectedUser.id">


                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" x-model="selectedUser.name" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="selectedUser.email" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" x-model="selectedUser.role" @change="filterDropdowns(selectedUser.role, true)" required
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('role') border-red-500 @enderror">
                            <option value="" disabled>-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="opd">OPD</option>
                            <option value="kecamatan">Kecamatan</option>
                            <option value="kelurahan">Kelurahan</option>
                        </select>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4" x-show="selectedUser.role === 'opd'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organisasi Perangkat Daerah (OPD)</label>
                        <select name="opd_id" x-model="selectedUser.opd_id" :required="selectedUser.role === 'opd'"
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('opd_id') border-red-500 @enderror">
                            <option value="">-- Pilih OPD --</option>
                            <template x-for="opd in filteredOpdsForSelect" :key="opd.id">
                                <option :value="opd.id" x-text="opd.nama_opd"></option>
                            </template>
                        </select>
                        @error('opd_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4" x-show="selectedUser.role === 'kecamatan' || selectedUser.role === 'kelurahan'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                        <select name="wilayah_id" x-model="selectedUser.wilayah_id" :required="selectedUser.role === 'kecamatan' || selectedUser.role === 'kelurahan'"
                            class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm @error('wilayah_id') border-red-500 @enderror">
                            <option value="">-- Pilih Wilayah --</option>
                            <template x-for="wilayah in filteredWilayahsForSelect" :key="wilayah.id">
                                <option :value="wilayah.id" x-text="wilayah.kelurahan ? wilayah.kelurahan + ' (' + wilayah.kecamatan + ')' : wilayah.kecamatan"></option>
                            </template>
                        </select>
                        @error('wilayah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4" x-data="{ show: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi (Kosongkan jika tidak diubah)</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" x-model="selectedUser.password"
                                class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10 @error('password') border-red-500 @enderror" autocomplete="new-password">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6" x-data="{ showConfirm: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi (Kosongkan jika tidak diubah)</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="selectedUser.password_confirmation"
                                class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10 @error('password_confirmation') border-red-500 @enderror" autocomplete="new-password">
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password_confirmation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#FE482B] text-white rounded hover:bg-orange-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> {{-- End of main x-data --}}
</x-navbar>
@endsection {{-- End of section content --}}