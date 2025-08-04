<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Jenis Kasus: {{ $caseType->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kode: {{ $caseType->code }} • {{ $caseType->status_text }} • Dibuat {{ $caseType->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $caseType)
                    <a href="{{ route('case-types.edit', $caseType) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        ✏️ Edit
                    </a>
                @endcan
                <a href="{{ route('case-types.index') }}" 
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
                
                <!-- Left Column - Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Informasi Dasar</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Kode Jenis Kasus</label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $caseType->code }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Status</label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $caseType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $caseType->status_text }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nama Jenis Kasus</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $caseType->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Urutan Tampilan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $caseType->sort_order }}</p>
                                </div>
                            </div>
                            
                            @if($caseType->description)
                                <div class="mt-6">
                                    <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $caseType->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Related Legal Cases -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">📁 Kasus Hukum Terkait</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $caseType->legalCases->count() }} kasus
                                </span>
                            </div>
                            
                            @if($caseType->legalCases->count() > 0)
                                <div class="space-y-3">
                                    @foreach($caseType->legalCases->take(10) as $case)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                                        {{ $case->progress_level }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $case->case_code }}</p>
                                                    <p class="text-xs text-gray-500">{{ $case->title }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $case->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $case->status_name }}
                                                </span>
                                                <a href="{{ route('legal-cases.show', $case) }}" 
                                                   class="text-blue-600 hover:text-blue-900 text-xs">
                                                    👁️ Lihat
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($caseType->legalCases->count() > 10)
                                        <div class="text-center py-2">
                                            <p class="text-sm text-gray-500">
                                                Dan {{ $caseType->legalCases->count() - 10 }} kasus lainnya...
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4">📁</div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada kasus</h4>
                                    <p class="text-sm text-gray-500">Belum ada kasus hukum yang menggunakan jenis kasus ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Actions & Meta -->
                <div class="space-y-6">
                    
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">⚡ Aksi Cepat</h3>
                            
                            <div class="space-y-3">
                                @can('update', $caseType)
                                    <a href="{{ route('case-types.edit', $caseType) }}" 
                                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                        ✏️ Edit Jenis Kasus
                                    </a>
                                    
                                    <form action="{{ route('case-types.toggle-status', $caseType) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-yellow-300 rounded-md shadow-sm text-sm font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                                            {{ $caseType->is_active ? '🔕 Nonaktifkan' : '🔔 Aktifkan' }}
                                        </button>
                                    </form>
                                @endcan
                                
                                @can('delete', $caseType)
                                    @if($caseType->legalCases->count() === 0)
                                        <form action="{{ route('case-types.destroy', $caseType) }}" method="POST" 
                                              onsubmit="return confirm('⚠️ Yakin ingin menghapus jenis kasus {{ $caseType->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                                🗑️ Hapus Jenis Kasus
                                            </button>
                                        </form>
                                    @else
                                        <div class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                                            🗑️ Tidak Dapat Dihapus
                                        </div>
                                        <p class="text-xs text-gray-500 text-center">
                                            Jenis kasus ini sedang digunakan
                                        </p>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Usage Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Statistik Penggunaan</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total Kasus</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ $caseType->legalCases->count() }}</span>
                                </div>
                                
                                @if($caseType->legalCases->count() > 0)
                                    @php
                                        $statusStats = $caseType->legalCases->groupBy('status')->map->count();
                                        $progressStats = $caseType->legalCases->groupBy('progress_level')->map->count();
                                    @endphp
                                    
                                    <hr class="border-gray-200">
                                    
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Status Kasus:</span>
                                        <div class="mt-2 space-y-1">
                                            @foreach($statusStats as $status => $count)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500">{{ ucfirst($status) }}</span>
                                                    <span class="font-medium">{{ $count }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <hr class="border-gray-200">
                                    
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Level Progress:</span>
                                        <div class="mt-2 space-y-1">
                                            @foreach($progressStats as $level => $count)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500">Level {{ $level }}</span>
                                                    <span class="font-medium">{{ $count }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Audit Trail -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Log Aktivitas</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 text-sm">➕</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Jenis kasus dibuat</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $caseType->created_at->format('d F Y, H:i') }} oleh {{ $caseType->creator->name }}
                                        </p>
                                    </div>
                                </div>
                                
                                @if($caseType->updated_at != $caseType->created_at)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 text-sm">✏️</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">Terakhir diperbarui</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $caseType->updated_at->format('d F Y, H:i') }} oleh {{ $caseType->updater->name ?? 'System' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>