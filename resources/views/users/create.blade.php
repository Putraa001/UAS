<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Pengguna Baru
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Buat akun pengguna baru dengan role yang sesuai
                </p>
            </div>
            <a href="{{ route('users.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                « Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Left Column - Form Fields -->
                            <div class="space-y-6">
                                <div class="text-center mb-6">
                                    <div class="h-20 w-20 mx-auto bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                        <span class="text-2xl text-white">👤</span>
                                    </div>
                                    <h3 class="mt-3 text-lg font-medium text-gray-900">Pengguna Baru</h3>
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        👤 Nama Lengkap *
                                    </label>
                                    <input id="name" 
                                           name="name" 
                                           type="text" 
                                           value="{{ old('name') }}"
                                           required 
                                           placeholder="Masukkan nama lengkap"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        📧 Email *
                                    </label>
                                    <input id="email" 
                                           name="email" 
                                           type="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           placeholder="contoh@bankbkk.com"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                        🏷️ Role Akses *
                                    </label>
                                    <select id="role" 
                                            name="role" 
                                            required 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                            👑 Administrator - Akses penuh sistem
                                        </option>
                                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>
                                            👔 Manager - Kelola kasus & pengguna
                                        </option>
                                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                                            👤 User - Akses kasus terbatas
                                        </option>
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column - Password & Info -->
                            <div class="space-y-6">
                                <!-- Password Section -->
                                <div class="bg-blue-50 rounded-lg p-6">
                                    <h3 class="text-sm font-medium text-blue-900 mb-4">🔐 Password & Keamanan</h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                                Password *
                                            </label>
                                            <input id="password" 
                                                   name="password" 
                                                   type="password" 
                                                   required 
                                                   placeholder="Minimal 8 karakter"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                                Konfirmasi Password *
                                            </label>
                                            <input id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   type="password" 
                                                   required 
                                                   placeholder="Ulangi password"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Info -->
                                <div class="bg-yellow-50 rounded-lg p-6">
                                    <h3 class="text-sm font-medium text-yellow-900 mb-4">ℹ️ Keterangan Role</h3>
                                    
                                    <div class="space-y-3 text-sm">
                                        <div class="flex items-start space-x-2">
                                            <span class="text-red-500">👑</span>
                                            <div>
                                                <span class="font-medium text-red-700">Administrator:</span>
                                                <p class="text-gray-600">Akses penuh sistem, kelola semua pengguna & kasus</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-2">
                                            <span class="text-blue-500">👔</span>
                                            <div>
                                                <span class="font-medium text-blue-700">Manager:</span>
                                                <p class="text-gray-600">Kelola kasus hukum, lihat semua kasus, buat laporan</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-2">
                                            <span class="text-green-500">👤</span>
                                            <div>
                                                <span class="font-medium text-green-700">User:</span>
                                                <p class="text-gray-600">Akses terbatas, hanya kasus yang ditugaskan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Note -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-xs font-medium text-gray-900 mb-2">🛡️ Catatan Keamanan</h4>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Password akan di-hash secara otomatis</li>
                                        <li>• Pengguna akan menerima email konfirmasi</li>
                                        <li>• Role dapat diubah sewaktu-waktu</li>
                                        <li>• Akun dapat di-nonaktifkan jika diperlukan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('users.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ❌ Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ✅ Buat Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>