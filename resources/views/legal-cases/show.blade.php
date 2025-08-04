<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Kasus: {{ $legalCase->case_code }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $legalCase->case_type_name }} • Dibuat {{ $legalCase->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $legalCase)
                    <a href="{{ route('legal-cases.edit', $legalCase) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        ✏️ Edit
                    </a>
                @endcan
                <a href="{{ route('legal-cases.index') }}" 
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Case Overview -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $legalCase->title }}</h3>
                                <div class="flex space-x-2">
                                    @if($legalCase->priority === 'kritis')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            🔥 Kritis
                                        </span>
                                    @elseif($legalCase->priority === 'tinggi')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            ⚡ Tinggi
                                        </span>
                                    @elseif($legalCase->priority === 'sedang')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            📋 Sedang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            📝 Rendah
                                        </span>
                                    @endif
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $legalCase->case_type_name }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="prose max-w-none">
                                <p class="text-gray-700 leading-relaxed">{{ $legalCase->description }}</p>
                            </div>
                            
                            @if($legalCase->notes)
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Catatan:</h4>
                                    <p class="text-sm text-gray-700">{{ $legalCase->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Update Form -->
                    @can('update', $legalCase)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Progress</h3>
                                
                                <form method="POST" action="{{ route('legal-cases.update-progress', $legalCase) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="progress_level" class="block text-sm font-medium text-gray-700 mb-1">
                                                Level Progress Baru <span class="text-red-500">*</span>
                                            </label>
                                            <select id="progress_level" name="progress_level" required
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @foreach(\App\Models\LegalCase::PROGRESS_LEVELS as $level => $data)
                                                    <option value="{{ $level }}" 
                                                            {{ $legalCase->progress_level == $level ? 'selected' : '' }}
                                                            {{ $level < $legalCase->progress_level && auth()->user()->role !== 'admin' ? 'disabled' : '' }}>
                                                        Level {{ $level }}: {{ $data['name'] }} ({{ $data['percentage'] }}%)
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if(auth()->user()->role !== 'admin')
                                                <p class="mt-1 text-xs text-gray-500">
                                                    * Hanya admin yang dapat menurunkan level progress
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                                Catatan Perubahan
                                            </label>
                                            <textarea id="notes" name="notes" rows="3"
                                                      placeholder="Jelaskan alasan perubahan progress..."
                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            📈 Update Progress
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endcan

                    <!-- Parties Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">👥 Pihak Terkait</h3>
                                @can('update', $legalCase)
                                    <a href="{{ route('case-parties.create', $legalCase) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        ➕ Tambah Pihak
                                    </a>
                                @endcan
                            </div>
                            
                            @if($legalCase->parties->count() > 0)
                                <!-- Parties Summary -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                    @php
                                        $partyStats = $legalCase->parties->groupBy('party_type')->map->count();
                                        $partyIcons = [
                                            'debitor' => ['icon' => '💳', 'color' => 'bg-red-500'],
                                            'penjamin' => ['icon' => '🤝', 'color' => 'bg-blue-500'],
                                            'saksi' => ['icon' => '👁️', 'color' => 'bg-green-500'],
                                            'ahli_waris' => ['icon' => '👨‍👩‍👧‍👦', 'color' => 'bg-purple-500'],
                                            'kuasa_hukum' => ['icon' => '⚖️', 'color' => 'bg-yellow-500'],
                                            'pihak_ketiga' => ['icon' => '👤', 'color' => 'bg-indigo-500'],
                                            'lainnya' => ['icon' => '📝', 'color' => 'bg-gray-500']
                                        ];
                                    @endphp
                                    
                                    @foreach(\App\Models\CaseParty::PARTY_TYPES as $type => $name)
                                        @if($partyStats->has($type))
                                            <div class="bg-white border border-gray-200 rounded-lg p-3">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-8 h-8 {{ $partyIcons[$type]['color'] }} rounded-full flex items-center justify-center text-white text-sm">
                                                            {{ $partyIcons[$type]['icon'] }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $name }}</p>
                                                        <p class="text-sm text-gray-500">{{ $partyStats[$type] }} orang</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Quick View of Primary Parties -->
                                <div class="space-y-3">
                                    @foreach($legalCase->parties->take(3) as $party)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($party->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $party->name }}</p>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $party->party_type_name }}
                                                        </span>
                                                        @if($party->phone)
                                                            <span>📱 {{ $party->phone }}</span>
                                                        @endif
                                                        @if($party->identity_number)
                                                            <span>🪪 {{ $party->identity_number }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('case-parties.show', [$legalCase, $party]) }}" 
                                                   class="text-blue-600 hover:text-blue-900 text-sm">
                                                    👁️ Lihat
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($legalCase->parties->count() > 3)
                                        <div class="text-center py-2">
                                            <a href="{{ route('case-parties.index', $legalCase) }}" 
                                               class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                Lihat semua {{ $legalCase->parties->count() }} pihak →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4">👥</div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada pihak terkait</h4>
                                    <p class="text-sm text-gray-500 mb-4">Tambahkan data debitor, penjamin, saksi, atau pihak lain yang terlibat dalam kasus ini</p>
                                    @can('update', $legalCase)
                                        <a href="{{ route('case-parties.create', $legalCase) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                            ➕ Tambah Pihak Pertama
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Dokumen Kasus</h3>
                                @can('update', $legalCase)
                                    <button onclick="toggleUploadForm()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        📎 Upload Dokumen
                                    </button>
                                @endcan
                            </div>
                            
                            <!-- Upload Form (Hidden by default) -->
                            @can('update', $legalCase)
                                <div id="uploadForm" class="hidden mb-4 p-4 bg-blue-50 rounded-lg">
                                    <form action="{{ route('documents.upload', $legalCase) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen</label>
                                                <input type="text" name="document_name" required 
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="Masukkan nama dokumen">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">File Dokumen</label>
                                                <input type="file" name="document" required 
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt,.xlsx,.xls"
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT, XLSX, XLS. Maksimal 2MB</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                                                <textarea name="description" rows="2" 
                                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                          placeholder="Deskripsi dokumen"></textarea>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    ✅ Upload
                                                </button>
                                                <button type="button" onclick="toggleUploadForm()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    ❌ Batal
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                            
                            @if($legalCase->documents->count() > 0)
                                <div class="space-y-3">
                                    @foreach($legalCase->documents as $document)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @if(str_contains($document->mime_type, 'pdf'))
                                                        📄
                                                    @elseif(str_contains($document->mime_type, 'image'))
                                                        🖼️
                                                    @elseif(str_contains($document->mime_type, 'word') || str_contains($document->mime_type, 'document'))
                                                        📝
                                                    @elseif(str_contains($document->mime_type, 'sheet') || str_contains($document->mime_type, 'excel'))
                                                        📊
                                                    @else
                                                        📎
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $document->document_name ?? $document->original_filename }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $document->original_filename }} • 
                                                        {{ $document->formatted_file_size }} • 
                                                        Upload {{ $document->created_at->diffForHumans() }} oleh {{ $document->uploader->name }}
                                                    </div>
                                                    @if($document->description)
                                                        <div class="text-xs text-gray-600 mt-1">
                                                            {{ $document->description }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('documents.view', $document) }}" target="_blank"
                                                   class="text-blue-600 hover:text-blue-900 text-sm">
                                                    👁️ Lihat
                                                </a>
                                                <a href="{{ route('documents.download', $document) }}"
                                                   class="text-green-600 hover:text-green-900 text-sm">
                                                    ⬇️ Download
                                                </a>
                                                @can('update', $legalCase)
                                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                                          class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                            🗑️ Hapus
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📂</div>
                                    <div class="text-sm">Belum ada dokumen yang diupload</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Progress Overview -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Saat Ini</h3>
                            
                            <div class="text-center mb-4">
                                <div class="text-3xl font-bold text-gray-900 mb-1">
                                    {{ $legalCase->progress_percentage }}%
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $legalCase->progress_level_name }}
                                </div>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                                @php
                                    $colors = [1 => 'yellow', 2 => 'blue', 3 => 'orange', 4 => 'green'];
                                    $color = $colors[$legalCase->progress_level];
                                @endphp
                                <div class="bg-{{ $color }}-500 h-3 rounded-full" 
                                     style="width: {{ $legalCase->progress_percentage }}%"></div>
                            </div>
                            
                            <div class="text-sm text-gray-600">
                                {{ $legalCase->progress_description }}
                            </div>
                        </div>
                    </div>

                    <!-- Case Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kasus</h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm text-gray-900">{{ $legalCase->status_name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pembuat Kasus</dt>
                                    <dd class="text-sm text-gray-900">{{ $legalCase->creator->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ditugaskan Kepada</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $legalCase->assignedUser?->name ?? 'Belum ditugaskan' }}
                                    </dd>
                                </div>
                                
                                @if($legalCase->due_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                                        <dd class="text-sm">
                                            @if($legalCase->isOverdue())
                                                <span class="text-red-600 font-medium">
                                                    {{ $legalCase->due_date->format('d M Y') }}
                                                </span>
                                                <div class="text-xs text-red-500">
                                                    Terlambat {{ abs($legalCase->getDaysUntilDue()) }} hari
                                                </div>
                                            @elseif($legalCase->isInDangerZone())
                                                <span class="text-orange-600 font-medium">
                                                    {{ $legalCase->due_date->format('d M Y') }}
                                                </span>
                                                <div class="text-xs text-orange-500">
                                                    Sisa {{ $legalCase->getDaysUntilDue() }} hari
                                                </div>
                                            @else
                                                <span class="text-gray-900">
                                                    {{ $legalCase->due_date->format('d M Y') }}
                                                </span>
                                                <div class="text-xs text-gray-500">
                                                    Sisa {{ $legalCase->getDaysUntilDue() }} hari
                                                </div>
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                                
                                @if($legalCase->estimated_resolution_days)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Estimasi Penyelesaian</dt>
                                        <dd class="text-sm text-gray-900">{{ $legalCase->estimated_resolution_days }} hari</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline Progress</h3>
                            
                            <div class="space-y-4">
                                @forelse($legalCase->statusLogs as $log)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($log->to_level == 4)
                                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs">✓</span>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs">{{ $log->to_level }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $log->to_level_name }} ({{ $log->to_percentage }}%)
                                            </div>
                                            @if($log->notes)
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ $log->notes }}
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $log->created_at->format('d M Y H:i') }} oleh {{ $log->changer->name }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500 text-center py-4">
                                        Belum ada update progress
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleUploadForm() {
            const uploadForm = document.getElementById('uploadForm');
            uploadForm.classList.toggle('hidden');
        }
    </script>
</x-app-layout>