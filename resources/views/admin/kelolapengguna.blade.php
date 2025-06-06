@extends('layouts.app')

@section('title', 'Kelola Pengguna')
<x-navbar>
    <div class="min-h-screen py-10 px-4 md:px-12">
        <div class="bg-white rounded-xl" x-data="{ showModal: false }">
            <div x-data="{
                showModal: false,
                showEditModal: false,
                selectedUser: { id: null, name: '', email: '', wilayah_id: '', wilayah_name: '', role: '', password: '', password_confirmation: '' }
            }" class="min-h-screen py-10 px-4 md:px-12">

                <div class="bg-white rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                            Kelola Pengguna
                        </h2>
                        <button @click="showModal = true"
                            class="flex items-center gap-2 bg-[#FE482B] hover:bg-orange-600 text-white px-4 py-2 rounded-md shadow transition">
                            <i class="fas fa-user-plus"></i>
                            Tambah Pengguna
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-lg shadow-inner">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#FE482B] text-white text-sm uppercase">
                                <tr>
                                    <th class="px-6 py-3 text-left tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Wilayah</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                                @foreach ($users as $index => $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->wilayah ? $user->wilayah->nama_wilayah : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-green-100 text-green-700',
                                                'opd' => 'bg-blue-100 text-blue-700',
                                                'kelurahan' => 'bg-yellow-100 text-yellow-700',
                                            ];
                                        @endphp
                                        <span class="inline-block {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold px-3 py-1 rounded-full">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                        <button
                                            @click="
                                                selectedUser = {
                                                    id: {{ $user->id }},
                                                    name: '{{ addslashes($user->name) }}',
                                                    email: '{{ addslashes($user->email) }}',
                                                    wilayah_id: '{{ $user->wilayah_id }}',
                                                    wilayah_name: '{{ $user->wilayah ? addslashes($user->wilayah->nama_wilayah) : '' }}',
                                                    role: '{{ $user->role }}',
                                                    password: '',
                                                    password_confirmation: ''
                                                };
                                                showEditModal = true
                                            "
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MODAL TAMBAH -->
                <div x-show="showModal" x-transition
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div @click.away="showModal = false"
                        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                        <button @click="showModal = false"
                                class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-xl font-semibold text-[#FE482B] mb-4 flex items-center gap-2">
                            <i class="fas fa-user-plus"></i> Tambah Pengguna
                        </h3>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <!-- Nama -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <!-- Email -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <!-- Wilayah (pakai select) -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                                <select name="wilayah_id" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                                    <option value="">-- Pilih Wilayah --</option>
                                    @foreach ($wilayahs as $wilayah)
                                        <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Role -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                                    <option value="admin">Admin</option>
                                    <option value="opd">OPD</option>
                                    <option value="kelurahan">Kelurahan</option>
                                </select>
                            </div>
                            <!-- Kata Sandi -->
                            <div class="mb-4" x-data="{ show: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10" required>
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Konfirmasi -->
                            <div class="mb-6" x-data="{ showConfirm: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi</label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10" required>
                                    <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                        <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
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

                <!-- MODAL EDIT -->
                <div x-show="showEditModal" x-transition
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div @click.away="showEditModal = false"
                        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
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

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" name="name" x-model="selectedUser.name" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" x-model="selectedUser.email" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                                <select name="wilayah_id" x-model="selectedUser.wilayah_id" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                                    <option value="">-- Pilih Wilayah --</option>
                                    @foreach ($wilayahs as $wilayah)
                                        <option :selected="selectedUser.wilayah_id == '{{ $wilayah->id }}'" value="{{ $wilayah->id }}">{{ $wilayah->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" x-model="selectedUser.role" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                                    <option value="admin">Admin</option>
                                    <option value="opd">OPD</option>
                                    <option value="kelurahan">Kelurahan</option>
                                </select>
                            </div>
                            <div class="mb-4" x-data="{ show: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" x-model="selectedUser.password"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10" autocomplete="new-password">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-6" x-data="{ showConfirm: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi</label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="selectedUser.password_confirmation"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10" autocomplete="new-password">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                        <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
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

            </div>
        </div>
    </div>
</x-navbar>