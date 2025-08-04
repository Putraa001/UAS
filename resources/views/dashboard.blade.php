<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Sistem Kasus Hukum Bank
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat datang, {{ $user->name }}!</h3>
                    <p class="text-gray-600">Role: <span class="font-semibold capitalize">{{ $user->role }}</span></p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Kasus -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">K</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Kasus</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalKasus }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kasus Aktif -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">A</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Kasus Aktif</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $kasusAktif }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kasus Selesai -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">S</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Kasus Selesai</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $kasusSelesai }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kasus Terlambat -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">!</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Terlambat</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $kasusTerlambat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kasus Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kasus Terbaru</h3>
                        <div class="space-y-4">
                            @forelse($recentCases as $case)
                                <div class="border-l-4 border-blue-400 pl-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900">{{ $case->case_code }}</h4>
                                        <span class="text-xs px-2 py-1 bg-gray-100 rounded-full">
                                            {{ $case->case_type_name }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($case->title, 50) }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-500">
                                            {{ $case->assignedUser?->name ?? 'Belum ditugaskan' }}
                                        </span>
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2" style="width: 60px;">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $case->progress_percentage }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $case->progress_percentage }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Belum ada kasus terbaru</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('legal-cases.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat semua kasus →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progress Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Progress</h3>
                        <div class="space-y-3">
                            @foreach([1 => 'Inisiasi', 2 => 'Investigasi', 3 => 'Penanganan', 4 => 'Selesai'] as $level => $name)
                                @php
                                    $count = $progressStats[$level] ?? 0;
                                    $percentage = $totalKasus > 0 ? round(($count / $totalKasus) * 100) : 0;
                                    $colors = [1 => 'yellow', 2 => 'blue', 3 => 'orange', 4 => 'green'];
                                    $color = $colors[$level];
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $name }}</span>
                                        <span class="text-sm text-gray-500">{{ $count }} ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Case Type Statistics -->
            @if(!empty($caseTypeStats))
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Jenis Kasus</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            @foreach(['KB' => 'Kredit Bermasalah', 'SN' => 'Sengketa Nasabah', 'CP' => 'Compliance', 'FR' => 'Fraud', 'LN' => 'Lainnya'] as $type => $name)
                                @php $count = $caseTypeStats[$type] ?? 0; @endphp
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $count }}</div>
                                    <div class="text-sm text-gray-500">{{ $name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Priority Alert -->
            @if($kasusBahaya > 0 || $kasusTerlambat > 0)
            <div class="mt-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-red-400">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Perhatian!</h3>
                            <div class="mt-2 text-sm text-red-700">
                                @if($kasusTerlambat > 0)
                                    <p>• {{ $kasusTerlambat }} kasus sudah melewati deadline</p>
                                @endif
                                @if($kasusBahaya > 0)
                                    <p>• {{ $kasusBahaya }} kasus mendekati deadline (≤3 hari)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
