<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Kasus Hukum
            </h2>
            @can('create', App\Models\LegalCase::class)
                <a href="{{ route('legal-cases.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Kasus Baru
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Search & Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('legal-cases.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Kasus</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Kode kasus, judul, atau deskripsi..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <!-- Case Type Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kasus</label>
                                <select name="case_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Jenis</option>
                                    @foreach(\App\Models\LegalCase::CASE_TYPES as $key => $name)
                                        <option value="{{ $key }}" {{ request('case_type') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Progress Level Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Level Progress</label>
                                <select name="progress_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Level</option>
                                    @foreach(\App\Models\LegalCase::PROGRESS_LEVELS as $level => $data)
                                        <option value="{{ $level }}" {{ request('progress_level') == $level ? 'selected' : '' }}>
                                            {{ $data['name'] }} ({{ $data['percentage'] }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Status</option>
                                    @foreach(\App\Models\LegalCase::STATUS_OPTIONS as $key => $name)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    🔍 Cari
                                </button>
                                <a href="{{ route('legal-cases.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    ⟲ Reset
                                </a>
                            </div>
                            <div class="text-sm text-gray-600">
                                Total: {{ $cases->total() }} kasus
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cases Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Kasus
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul & Jenis
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Progress
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penugasan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deadline
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($cases as $case)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $case->case_code }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $case->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 mb-1">
                                            {{ Str::limit($case->title, 40) }}
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $case->case_type_name }}
                                            </span>
                                            @if($case->priority === 'kritis')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    🔥 Kritis
                                                </span>
                                            @elseif($case->priority === 'tinggi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    ⚡ Tinggi
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-full mr-2">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs font-medium text-gray-700">
                                                        {{ $case->progress_level_name }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $case->progress_percentage }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    @php
                                                        $colors = [1 => 'yellow', 2 => 'blue', 3 => 'orange', 4 => 'green'];
                                                        $color = $colors[$case->progress_level];
                                                    @endphp
                                                    <div class="bg-{{ $color }}-500 h-2 rounded-full" 
                                                         style="width: {{ $case->progress_percentage }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($case->assignedUser)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                        <span class="text-xs font-medium text-white">
                                                            {{ substr($case->assignedUser->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $case->assignedUser->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($case->due_date)
                                            @if($case->isOverdue())
                                                <span class="text-red-600 font-medium">
                                                    ⚠️ {{ $case->due_date->format('d/m/Y') }}
                                                </span>
                                                <div class="text-xs text-red-500">
                                                    Terlambat {{ abs($case->getDaysUntilDue()) }} hari
                                                </div>
                                            @elseif($case->isInDangerZone())
                                                <span class="text-orange-600 font-medium">
                                                    ⏰ {{ $case->due_date->format('d/m/Y') }}
                                                </span>
                                                <div class="text-xs text-orange-500">
                                                    Sisa {{ $case->getDaysUntilDue() }} hari
                                                </div>
                                            @else
                                                <span class="text-gray-600">
                                                    {{ $case->due_date->format('d/m/Y') }}
                                                </span>
                                                <div class="text-xs text-gray-500">
                                                    Sisa {{ $case->getDaysUntilDue() }} hari
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada deadline</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('legal-cases.show', $case) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                👁️ Lihat
                                            </a>
                                            @can('update', $case)
                                                <a href="{{ route('legal-cases.edit', $case) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    ✏️ Edit
                                                </a>
                                            @endcan
                                            @can('delete', $case)
                                                <form method="POST" action="{{ route('legal-cases.destroy', $case) }}" 
                                                      onsubmit="return confirm('Yakin ingin hapus kasus {{ $case->case_code }}?')" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <div class="text-2xl mb-2">📂</div>
                                            <div class="text-lg font-medium mb-1">Belum ada kasus hukum</div>
                                            <div class="text-sm">
                                                @can('create', App\Models\LegalCase::class)
                                                    <a href="{{ route('legal-cases.create') }}" class="text-blue-600 hover:text-blue-800">
                                                        Buat kasus pertama →
                                                    </a>
                                                @else
                                                    Hubungi admin untuk membuat kasus baru
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $cases->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>