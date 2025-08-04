<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📋 Manajemen Jenis Kasus
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola jenis kasus hukum yang tersedia dalam sistem
                </p>
            </div>
            <div class="flex space-x-2">
                @can('create', App\Models\CaseType::class)
                    <a href="{{ route('case-types.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        ➕ Tambah Jenis Kasus
                    </a>
                @endcan
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

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-red-400">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Ada beberapa error:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('case-types.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-64">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                Cari Jenis Kasus
                            </label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan kode, nama, atau deskripsi..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status
                            </label>
                            <select id="status" name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                🔍 Cari
                            </button>
                            <a href="{{ route('case-types.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                🔄 Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Case Types List -->
            @if($caseTypes->count() > 0)
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach($caseTypes as $caseType)
                            <li>
                                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <!-- Case Type Icon & Info -->
                                            <div class="flex-shrink-0">
                                                <div class="h-12 w-12 rounded-lg {{ $caseType->is_active ? 'bg-blue-500' : 'bg-gray-400' }} flex items-center justify-center text-white font-bold text-lg">
                                                    {{ $caseType->code }}
                                                </div>
                                            </div>
                                            
                                            <div class="ml-4 flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $caseType->name }}
                                                    </p>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $caseType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $caseType->status_text }}
                                                    </span>
                                                </div>
                                                
                                                @if($caseType->description)
                                                    <p class="mt-1 text-sm text-gray-500 truncate">
                                                        {{ $caseType->description }}
                                                    </p>
                                                @endif
                                                
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span>📊 Urutan: {{ $caseType->sort_order }}</span>
                                                    <span>📁 {{ $caseType->legalCases->count() }} kasus</span>
                                                    <span>👤 Dibuat oleh {{ $caseType->creator->name }}</span>
                                                    <span>📅 {{ $caseType->created_at->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            <a href="{{ route('case-types.show', $caseType) }}" 
                                               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                                👁️ Lihat
                                            </a>
                                            @can('update', $caseType)
                                                <a href="{{ route('case-types.edit', $caseType) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-indigo-300 rounded-md text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                                    ✏️ Edit
                                                </a>
                                                <form action="{{ route('case-types.toggle-status', $caseType) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 border border-yellow-300 rounded-md text-xs font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                                                        {{ $caseType->is_active ? '🔕 Nonaktifkan' : '🔔 Aktifkan' }}
                                                    </button>
                                                </form>
                                            @endcan
                                            @can('delete', $caseType)
                                                <form action="{{ route('case-types.destroy', $caseType) }}" method="POST" 
                                                      class="inline" onsubmit="return confirm('⚠️ Yakin ingin menghapus jenis kasus {{ $caseType->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $caseTypes->links('pagination::simple-tailwind') }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan {{ $caseTypes->firstItem() }} hingga {{ $caseTypes->lastItem() }} 
                                dari {{ $caseTypes->total() }} jenis kasus
                            </p>
                        </div>
                        <div>
                            {{ $caseTypes->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-12 sm:px-6 text-center">
                        <div class="space-y-4">
                            <div class="text-6xl">📋</div>
                            <div class="text-lg font-medium text-gray-900">
                                {{ request()->hasAny(['search', 'status']) ? 'Tidak ada jenis kasus yang ditemukan' : 'Belum ada jenis kasus' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ request()->hasAny(['search', 'status']) ? 'Coba ubah kriteria pencarian atau filter' : 'Tambahkan jenis kasus pertama untuk sistem' }}
                            </div>
                            @can('create', App\Models\CaseType::class)
                                <div class="pt-4">
                                    <a href="{{ route('case-types.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        ➕ Tambah Jenis Kasus Pertama
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>