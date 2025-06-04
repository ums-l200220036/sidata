<x-navbar>
    <div class="min-h-screenpy-10 px-4 md:px-12">
        <div class="bg-white rounded-xl" x-data="{ showModal: false }">
           <!-- Wrapper dengan Alpine.js -->
            <div x-data="{
                showModal: false,
                showEditModal: false,
                selectedUser: { id: null, name: '', email: '', role: '' }
            }" class="min-h-screen py-10 px-4 md:px-12">

                <div class="bg-white rounded-xl  p-6">
                    <!-- Header -->
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

                    <!-- TABEL -->
                    <div class="overflow-x-auto rounded-lg shadow-inner">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#FE482B] text-white text-sm uppercase">
                                <tr>
                                    <th class="px-6 py-3 text-left tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Wilayah</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                                <!-- Baris 1 -->
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">1</td>
                                    <td class="px-6 py-4 font-medium">John Doe</td>
                                    <td class="px-6 py-4">john@example.com</td>
                                    <td class="px-6 py-4">Kecamatan 1</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            Admin
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                        <button 
                                            @click="
                                                selectedUser = {
                                                    id: 1,
                                                    name: 'John Doe',
                                                    email: 'john@example.com',
                                                    wilayah: 'Kecamatan 1',
                                                    role: 'admin',
                                                    password: '',
                                                    password_confirmation: ''
                                                }; 
                                                showEditModal = true"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button class="inline-flex items-center text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </td>
                                </tr>

                                <!-- Baris 2 -->
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">2</td>
                                    <td class="px-6 py-4 font-medium">Jane Smith</td>
                                    <td class="px-6 py-4">jane@example.com</td>
                                    <td class="px-6 py-4">Kelurahan ABC</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            OPD
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                        <button 
                                            @click="
                                                selectedUser = {
                                                    id: 2,
                                                    name: 'Jane Smith',
                                                    email: 'jane@example.com',
                                                    wilayah: 'Kelurahan ABC',
                                                    role: 'opd',
                                                    password: '',
                                                    password_confirmation: ''
                                                }; 
                                                showEditModal = true"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button class="inline-flex items-center text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
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
                            <!-- Wilayah -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                                <input type="text" name="wilayah" required
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
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
                                    <input :type="show ? 'text' : 'password'" x-model="selectedUser.password"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10">
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
                                    <input :type="showConfirm ? 'text' : 'password'" x-model="selectedUser.password_confirmation"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                        <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Tombol -->
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
                            <i class="fas fa-user-edit"></i> Edit Pengguna
                        </h3>
                        <form @submit.prevent="console.log(selectedUser)">
                            <!-- Nama -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" x-model="selectedUser.name"
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <!-- Email -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" x-model="selectedUser.email"
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <!-- Wilayah -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                                <input type="text" x-model="selectedUser.wilayah"
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                            </div>
                            <!-- Role -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select x-model="selectedUser.role"
                                    class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm">
                                    <option value="admin">Admin</option>
                                    <option value="opd">OPD</option>
                                    <option value="kelurahan">Kelurahan</option>
                                </select>
                            </div>
                            <!-- Kata Sandi -->
                            <div class="mb-4" x-data="{ show: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi (Opsional)</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" x-model="selectedUser.password"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10">
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
                                    <input :type="showConfirm ? 'text' : 'password'" x-model="selectedUser.password_confirmation"
                                        class="w-full px-4 py-2 border rounded focus:ring-[#FE482B] focus:border-[#FE482B] shadow-sm pr-10">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#FE482B] focus:outline-none">
                                        <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Tombol -->
                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="showEditModal = false"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-[#FE482B] text-white rounded hover:bg-orange-600">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-navbar>
