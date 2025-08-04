<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Pengguna: {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Ubah informasi pengguna dan role akses
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
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-green-400">✅</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        👤 Nama Lengkap
                                    </label>
                                    <input id="name" 
                                           name="name" 
                                           type="text" 
                                           value="{{ old('name', $user->name) }}"
                                           required 
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        📧 Email
                                    </label>
                                    <input id="email" 
                                           name="email" 
                                           type="email" 
                                           value="{{ old('email', $user->email) }}"
                                           required 
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                        🏷️ Role Akses
                                    </label>
                                    <select id="role" 
                                            name="role" 
                                            required 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                            👑 Administrator
                                        </option>
                                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>
                                            👔 Manager
                                        </option>
                                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                            👤 User
                                        </option>
                                    </select>
                                    @if($user->id === auth()->id())
                                        <p class="mt-1 text-xs text-gray-500">Role sendiri tidak dapat diubah</p>
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                    @endif
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Current User Info -->
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h3 class="text-sm font-medium text-blue-900 mb-3">ℹ️ Informasi Saat Ini</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">User ID:</span>
                                            <span class="font-medium">{{ $user->id }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Role Saat Ini:</span>
                                            <span class="font-medium">{{ ucfirst($user->role) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Bergabung:</span>
                                            <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Terakhir Update:</span>
                                            <span class="font-medium">{{ $user->updated_at->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Change -->
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <h3 class="text-sm font-medium text-yellow-900 mb-3">🔐 Ubah Password (Opsional)</h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                                                Password Baru
                                            </label>
                                            <input id="password" 
                                                   name="password" 
                                                   type="password" 
                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                                            @error('password')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">
                                                Konfirmasi Password
                                            </label>
                                            <input id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   type="password" 
                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                                        </div>

                                        <p class="text-xs text-gray-500">
                                            💡 Kosongkan jika tidak ingin mengubah password
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('users.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ❌ Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ✅ Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>