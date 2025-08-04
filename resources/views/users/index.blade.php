<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Manajemen Pengguna
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola pengguna dan role akses sistem
                </p>
            </div>
            <a href="{{ route('users.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ➕ Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-red-400">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-900">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">
                                        👤 Pengguna
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">
                                        🏷️ Role & Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">
                                        📅 Bergabung
                                    </th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-white">
                                        ⚙️ Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center space-x-4">
                                                <div class="relative">
                                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    @if($user->id === auth()->id())
                                                        <div class="absolute -top-1 -right-1 h-4 w-4 bg-yellow-400 rounded-full flex items-center justify-center">
                                                            <span class="text-xs">👑</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2">
                                                        <p class="text-base font-semibold text-gray-900 truncate">
                                                            {{ $user->name }}
                                                        </p>
                                                        @if($user->id === auth()->id())
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                Anda
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        📧 {{ $user->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="space-y-3">
                                                @php
                                                    $roleConfig = [
                                                        'admin' => ['bg' => 'bg-red-500', 'text' => 'text-white', 'icon' => '👑', 'name' => 'Administrator'],
                                                        'manager' => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'icon' => '👔', 'name' => 'Manager'],
                                                        'user' => ['bg' => 'bg-green-500', 'text' => 'text-white', 'icon' => '👤', 'name' => 'User']
                                                    ];
                                                    $config = $roleConfig[$user->role];
                                                @endphp
                                                
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                                        {{ $config['icon'] }} {{ $config['name'] }}
                                                    </span>
                                                </div>
                                                
                                                @if($user->id !== auth()->id())
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-gray-500">Ubah ke:</span>
                                                        <form action="{{ route('users.update-role', $user) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <select name="role" onchange="if(confirm('Ubah role {{ $user->name }}?')) this.form.submit()" 
                                                                    class="text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                <option value="">-- Pilih Role --</option>
                                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>👑 Admin</option>
                                                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>👔 Manager</option>
                                                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>👤 User</option>
                                                            </select>
                                                        </form>
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-400 italic">Role tidak dapat diubah</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="space-y-1">
                                                <div class="flex items-center text-sm text-gray-900">
                                                    📅 {{ $user->created_at->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ⏰ {{ $user->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('users.show', $user) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                                    👁️ Lihat
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-indigo-300 rounded-md text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors duration-200">
                                                    ✏️ Edit
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                                          class="inline" onsubmit="return confirm('⚠️ PERINGATAN!\n\nYakin ingin menghapus pengguna {{ $user->name }}?\nTindakan ini tidak dapat dibatalkan!')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                                            🗑️ Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium text-gray-500 bg-gray-100">
                                                        🔒 Protected
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="space-y-3">
                                                <div class="text-4xl">👥</div>
                                                <div class="text-lg font-medium text-gray-900">Tidak ada pengguna</div>
                                                <div class="text-sm text-gray-500">Belum ada pengguna yang terdaftar dalam sistem</div>
                                                <div class="pt-4">
                                                    <a href="{{ route('users.create') }}" 
                                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                        ➕ Tambah Pengguna Pertama
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>