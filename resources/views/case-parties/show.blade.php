<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail {{ $party->party_type_name }}: {{ $party->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kasus: {{ $legalCase->case_code }} - {{ $legalCase->title }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $legalCase)
                    <a href="{{ route('case-parties.edit', [$legalCase, $party]) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        ✏️ Edit
                    </a>
                @endcan
                <a href="{{ route('case-parties.index', $legalCase) }}" 
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
                
                <!-- Left Column - Profile Card -->
                <div class="lg:col-span-1">
                    <!-- Profile Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                <!-- Avatar -->
                                <div class="relative inline-block">
                                    <div class="h-24 w-24 mx-auto bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                        {{ strtoupper(substr($party->name, 0, 1)) }}
                                    </div>
                                    
                                    @php
                                        $statusColors = [
                                            'aktif' => 'bg-green-400',
                                            'non_aktif' => 'bg-yellow-400',
                                            'meninggal' => 'bg-red-400',
                                            'pindah' => 'bg-blue-400'
                                        ];
                                    @endphp
                                    <div class="absolute -bottom-1 -right-1 h-6 w-6 {{ $statusColors[$party->status] }} rounded-full border-2 border-white"></div>
                                </div>

                                <!-- Basic Info -->
                                <div class="mt-4">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $party->name }}</h3>
                                    
                                    <!-- Party Type Badge -->
                                    @php
                                        $partyColors = [
                                            'debitor' => 'bg-red-500',
                                            'penjamin' => 'bg-blue-500',
                                            'saksi' => 'bg-green-500',
                                            'ahli_waris' => 'bg-purple-500',
                                            'kuasa_hukum' => 'bg-yellow-500',
                                            'pihak_ketiga' => 'bg-indigo-500',
                                            'lainnya' => 'bg-gray-500'
                                        ];
                                    @endphp
                                    
                                    <div class="mt-3 flex justify-center space-x-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white {{ $partyColors[$party->party_type] }}">
                                            {{ $party->party_type_name }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $party->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $party->status_name }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                @can('update', $legalCase)
                                    <div class="mt-6 flex justify-center space-x-3">
                                        <a href="{{ route('case-parties.edit', [$legalCase, $party]) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
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
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">📞 Kontak</h4>
                            
                            <div class="space-y-3">
                                @if($party->phone)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-gray-400">📱</span>
                                        <div>
                                            <p class="text-sm text-gray-600">Telepon</p>
                                            <p class="font-medium">{{ $party->phone }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($party->email)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-gray-400">📧</span>
                                        <div>
                                            <p class="text-sm text-gray-600">Email</p>
                                            <p class="font-medium">{{ $party->email }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($party->emergency_contact)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-gray-400">🚨</span>
                                        <div>
                                            <p class="text-sm text-gray-600">Kontak Darurat</p>
                                            <p class="font-medium">{{ $party->emergency_contact }}</p>
                                            @if($party->emergency_phone)
                                                <p class="text-sm text-gray-500">{{ $party->emergency_phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Columns - Details -->
                <div class="lg:col-span-2">
                    
                    <!-- Personal Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">👤 Informasi Personal</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    @if($party->identity_number)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">{{ $party->identity_type_name }}</label>
                                            <p class="text-sm text-gray-900">{{ $party->identity_number }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($party->gender)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                                            <p class="text-sm text-gray-900">{{ $party->gender_name }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($party->place_of_birth || $party->date_of_birth)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</label>
                                            <p class="text-sm text-gray-900">
                                                {{ $party->place_of_birth ? $party->place_of_birth : '-' }}{{ $party->date_of_birth ? ', ' . $party->date_of_birth->format('d F Y') : '' }}
                                                @if($party->age)
                                                    <span class="text-gray-500">({{ $party->age }} tahun)</span>
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                    
                                    @if($party->marital_status)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Status Pernikahan</label>
                                            <p class="text-sm text-gray-900">{{ $party->marital_status_name }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="space-y-4">
                                    @if($party->occupation)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Pekerjaan</label>
                                            <p class="text-sm text-gray-900">{{ $party->occupation }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($party->education)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Pendidikan</label>
                                            <p class="text-sm text-gray-900">{{ $party->education }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">📍 Alamat</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Alamat Lengkap</label>
                                    <p class="text-sm text-gray-900">{{ $party->full_address }}</p>
                                </div>
                                
                                @if($party->postal_code)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Kode Pos</label>
                                        <p class="text-sm text-gray-900">{{ $party->postal_code }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information (for debitor/penjamin) -->
                    @if(in_array($party->party_type, ['debitor', 'penjamin']) && ($party->monthly_income || $party->debt_amount || $party->collateral_description))
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">💰 Informasi Keuangan</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if($party->monthly_income)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Penghasilan Bulanan</label>
                                            <p class="text-lg font-semibold text-green-600">{{ $party->formatted_monthly_income }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($party->debt_amount)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Jumlah Hutang</label>
                                            <p class="text-lg font-semibold text-red-600">{{ $party->formatted_debt_amount }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($party->collateral_description)
                                    <div class="mt-4">
                                        <label class="text-sm font-medium text-gray-500">Deskripsi Jaminan</label>
                                        <p class="text-sm text-gray-900 mt-1">{{ $party->collateral_description }}</p>
                                        @if($party->collateral_value)
                                            <p class="text-sm font-semibold text-blue-600 mt-1">
                                                Nilai: {{ $party->formatted_collateral_value }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($party->notes)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">📝 Catatan</h4>
                                <div class="prose prose-sm max-w-none">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $party->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Audit Trail -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">📊 Log Aktivitas</h4>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 text-sm">➕</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Data dibuat</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $party->created_at->format('d F Y, H:i') }} oleh {{ $party->creator->name }}
                                        </p>
                                    </div>
                                </div>
                                
                                @if($party->updated_at != $party->created_at)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 text-sm">✏️</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">Terakhir diupdate</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $party->updated_at->format('d F Y, H:i') }} oleh {{ $party->updater->name ?? 'System' }}
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