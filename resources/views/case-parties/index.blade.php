<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Data Pihak Terkait
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kasus: {{ $legalCase->case_code }} - {{ $legalCase->title }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $legalCase)
                    <a href="{{ route('case-parties.create', $legalCase) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        ➕ Tambah Pihak
                    </a>
                @endcan
                <a href="{{ route('legal-cases.show', $legalCase) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    « Kembali ke Kasus
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

            @if($parties->count() > 0)
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @php
                        $partyStats = $parties->groupBy('party_type')->map->count();
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
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 {{ $partyIcons[$type]['color'] }} rounded-full flex items-center justify-center text-white text-sm">
                                                {{ $partyIcons[$type]['icon'] }}
                                            </div>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">{{ $name }}</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $partyStats[$type] }} orang</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Parties List -->
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach($parties->groupBy('party_type') as $type => $typeParties)
                            <!-- Group Header -->
                            <li class="bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 {{ $partyIcons[$type]['color'] }} rounded-full flex items-center justify-center text-white">
                                                    {{ $partyIcons[$type]['icon'] }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ \App\Models\CaseParty::PARTY_TYPES[$type] }}
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ $typeParties->count() }} orang terdaftar</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Parties in this type -->
                            @foreach($typeParties as $party)
                                <li>
                                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <!-- Avatar -->
                                                <div class="flex-shrink-0">
                                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                        {{ strtoupper(substr($party->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                
                                                <!-- Party Info -->
                                                <div class="ml-4 flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $party->name }}
                                                        </p>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $party->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $party->status_name }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                        @if($party->identity_number)
                                                            <span>🪪 {{ $party->identity_type_name }}: {{ $party->identity_number }}</span>
                                                        @endif
                                                        @if($party->phone)
                                                            <span>📱 {{ $party->phone }}</span>
                                                        @endif
                                                        @if($party->age)
                                                            <span>📅 {{ $party->age }} tahun</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($party->address)
                                                        <p class="mt-1 text-sm text-gray-500 truncate">
                                                            📍 {{ $party->full_address }}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($party->debt_amount && $type === 'debitor')
                                                        <p class="mt-1 text-sm text-red-600">
                                                            💰 Hutang: {{ $party->formatted_debt_amount }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center space-x-2 ml-4">
                                                <a href="{{ route('case-parties.show', [$legalCase, $party]) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                                    👁️ Lihat
                                                </a>
                                                @can('update', $legalCase)
                                                    <a href="{{ route('case-parties.edit', [$legalCase, $party]) }}" 
                                                       class="inline-flex items-center px-3 py-1 border border-indigo-300 rounded-md text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                                        ✏️ Edit
                                                    </a>
                                                    <form action="{{ route('case-parties.destroy', [$legalCase, $party]) }}" method="POST" 
                                                          class="inline" onsubmit="return confirm('⚠️ Yakin ingin menghapus data {{ $party->name }}?')">
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
                        @endforeach
                    </ul>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-12 sm:px-6 text-center">
                        <div class="space-y-4">
                            <div class="text-6xl">👥</div>
                            <div class="text-lg font-medium text-gray-900">Belum ada data pihak terkait</div>
                            <div class="text-sm text-gray-500">Tambahkan data debitor, penjamin, saksi, atau pihak lain yang terlibat dalam kasus ini</div>
                            @can('update', $legalCase)
                                <div class="pt-4">
                                    <a href="{{ route('case-parties.create', $legalCase) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        ➕ Tambah Pihak Pertama
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