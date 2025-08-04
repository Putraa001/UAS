<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Pengguna: {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Informasi lengkap dan aktivitas pengguna
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Edit
                </a>
                <a href="{{ route('users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    « Kembali
                </a>
            </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column - User Profile -->
                <div class="lg:col-span-1">
                    <!-- Profile Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                <!-- Avatar -->
                                <div class="relative inline-block">
                                    <div class="h-24 w-24 mx-auto bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    @if($user->id === auth()->id())
                                        <div class="absolute -top-2 -right-2 h-8 w-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                            <span class="text-sm">👑</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- User Info -->
                                <div class="mt-4">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-gray-600">{{ $user->email }}</p>
                                    
                                    <!-- Role Badge -->
                                    @php
                                        $roleConfig = [
                                            'admin' => ['bg' => 'bg-red-500', 'text' => 'text-white', 'icon' => '👑', 'name' => 'Administrator'],
                                            'manager' => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'icon' => '👔', 'name' => 'Manager'],
                                            'user' => ['bg' => 'bg-green-500', 'text' => 'text-white', 'icon' => '👤', 'name' => 'User']
                                        ];
                                        $config = $roleConfig[$user->role];
                                    @endphp
                                    
                                    <div class="mt-3">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                            {{ $config['icon'] }} {{ $config['name'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="mt-6 flex justify-center space-x-3">
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                        ✏️ Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                              class="inline" onsubmit="return confirm('⚠️ Yakin ingin menghapus {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Stats -->
                    <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">📊 Statistik</h4>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">⚖️ Kasus Dibuat:</span>
                                    <span class="font-semibold text-blue-600">{{ $user->createdCases->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">📋 Kasus Ditugaskan:</span>
                                    <span class="font-semibold text-green-600">{{ $user->assignedCases->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">📅 Bergabung:</span>
                                    <span class="font-semibold">{{ $user->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">🕒 Aktif:</span>
                                    <span class="font-semibold">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Activities -->
                <div class="lg:col-span-2">
                    
                    <!-- User Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">ℹ️ Informasi Detail</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">User ID</label>
                                        <p class="text-sm text-gray-900">{{ $user->id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                        <p class="text-sm text-gray-900">{{ $user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Email</label>
                                        <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Role</label>
                                        <p class="text-sm text-gray-900">{{ $config['icon'] }} {{ $config['name'] }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                                        <p class="text-sm text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                                        <p class="text-sm text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Created Cases -->
                    @if($user->createdCases->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">⚖️ Kasus yang Dibuat ({{ $user->createdCases->count() }})</h4>
                                
                                <div class="space-y-3">
                                    @foreach($user->createdCases->take(5) as $case)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-blue-600">{{ $case->case_code }}</span>
                                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                        {{ $case->case_type_name }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-900 mt-1">{{ $case->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $case->created_at->format('d M Y') }}</p>
                                            </div>
                                            <a href="{{ route('legal-cases.show', $case) }}" 
                                               class="text-blue-600 hover:text-blue-900 text-sm">👁️</a>
                                        </div>
                                    @endforeach
                                    
                                    @if($user->createdCases->count() > 5)
                                        <p class="text-xs text-gray-500 text-center pt-2">
                                            Dan {{ $user->createdCases->count() - 5 }} kasus lainnya...
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Assigned Cases -->
                    @if($user->assignedCases->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">📋 Kasus yang Ditugaskan ({{ $user->assignedCases->count() }})</h4>
                                
                                <div class="space-y-3">
                                    @foreach($user->assignedCases->take(5) as $case)
                                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-green-600">{{ $case->case_code }}</span>
                                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                                        {{ $case->case_type_name }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-900 mt-1">{{ $case->title }}</p>
                                                <p class="text-xs text-gray-500">Assigned {{ $case->created_at->format('d M Y') }}</p>
                                            </div>
                                            <a href="{{ route('legal-cases.show', $case) }}" 
                                               class="text-green-600 hover:text-green-900 text-sm">👁️</a>
                                        </div>
                                    @endforeach
                                    
                                    @if($user->assignedCases->count() > 5)
                                        <p class="text-xs text-gray-500 text-center pt-2">
                                            Dan {{ $user->assignedCases->count() - 5 }} kasus lainnya...
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>