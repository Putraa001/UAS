<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Kasus Hukum Baru
            </h2>
            <a href="{{ route('legal-cases.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                « Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="text-red-400">⚠️</span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Ada beberapa error yang perlu diperbaiki:
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

                    <form method="POST" action="{{ route('legal-cases.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Case Type -->
                        <div>
                            <label for="case_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kasus <span class="text-red-500">*</span>
                            </label>
                            <select id="case_type" name="case_type" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih jenis kasus...</option>
                                @foreach($caseTypes as $caseType)
                                    <option value="{{ $caseType->code }}" {{ old('case_type') == $caseType->code ? 'selected' : '' }}
                                            title="{{ $caseType->description }}">
                                        {{ $caseType->code }} - {{ $caseType->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                Kode kasus akan auto-generate berdasarkan jenis (contoh: KB-2025-08-001)
                            </p>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Judul Kasus <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   placeholder="Masukkan judul kasus yang deskriptif (min. 10 karakter)"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi Kasus <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" required
                                      placeholder="Jelaskan detail kasus secara lengkap (min. 20 karakter)"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Assigned To -->
                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">
                                    Ditugaskan Kepada
                                </label>
                                <select id="assigned_to" name="assigned_to"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih penugasan...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ ucfirst($user->role) }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    Bisa ditugaskan nanti jika belum ditentukan
                                </p>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prioritas
                                </label>
                                <select id="priority" name="priority"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach(\App\Models\LegalCase::PRIORITY_OPTIONS as $key => $name)
                                        <option value="{{ $key }}" {{ old('priority', 'sedang') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Deadline
                                </label>
                                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" 
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">
                                    Target penyelesaian kasus (opsional)
                                </p>
                            </div>

                            <!-- Estimated Resolution Days -->
                            <div>
                                <label for="estimated_resolution_days" class="block text-sm font-medium text-gray-700 mb-1">
                                    Estimasi Hari Penyelesaian
                                </label>
                                <input type="number" id="estimated_resolution_days" name="estimated_resolution_days" 
                                       value="{{ old('estimated_resolution_days') }}" min="1" max="365"
                                       placeholder="Contoh: 30"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">
                                    Perkiraan jumlah hari untuk menyelesaikan kasus
                                </p>
                            </div>
                        </div>

                        <!-- Primary Party Information -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-yellow-900 mb-4">👤 Informasi Pihak Utama</h3>
                            <p class="text-sm text-yellow-700 mb-4">
                                Masukkan data pihak yang bermasalah/debitor utama dalam kasus ini
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Primary Party Name -->
                                <div>
                                    <label for="primary_party_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Pihak Utama <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="primary_party_name" name="primary_party_name" 
                                           value="{{ old('primary_party_name') }}" required
                                           placeholder="Nama lengkap debitor/yang bermasalah"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                    <p class="mt-1 text-sm text-gray-500">
                                        Bisa perorangan atau nama perusahaan/PT
                                    </p>
                                </div>

                                <!-- Primary Party Type -->
                                <div>
                                    <label for="primary_party_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Pihak <span class="text-red-500">*</span>
                                    </label>
                                    <select id="primary_party_type" name="primary_party_type" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        <option value="">Pilih jenis pihak...</option>
                                        <option value="debitor" {{ old('primary_party_type') == 'debitor' ? 'selected' : '' }}>
                                            Debitor (Yang berutang)
                                        </option>
                                        <option value="penjamin" {{ old('primary_party_type') == 'penjamin' ? 'selected' : '' }}>
                                            Penjamin
                                        </option>
                                        <option value="pihak_ketiga" {{ old('primary_party_type') == 'pihak_ketiga' ? 'selected' : '' }}>
                                            Pihak Ketiga
                                        </option>
                                        <option value="lainnya" {{ old('primary_party_type') == 'lainnya' ? 'selected' : '' }}>
                                            Lainnya
                                        </option>
                                    </select>
                                </div>

                                <!-- Identity Type & Number -->
                                <div>
                                    <label for="primary_identity_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Identitas
                                    </label>
                                    <select id="primary_identity_type" name="primary_identity_type"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        <option value="">Pilih jenis identitas...</option>
                                        <option value="ktp" {{ old('primary_identity_type') == 'ktp' ? 'selected' : '' }}>KTP</option>
                                        <option value="sim" {{ old('primary_identity_type') == 'sim' ? 'selected' : '' }}>SIM</option>
                                        <option value="passport" {{ old('primary_identity_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="npwp" {{ old('primary_identity_type') == 'npwp' ? 'selected' : '' }}>NPWP</option>
                                        <option value="siup" {{ old('primary_identity_type') == 'siup' ? 'selected' : '' }}>SIUP (Perusahaan)</option>
                                        <option value="akta" {{ old('primary_identity_type') == 'akta' ? 'selected' : '' }}>Akta Pendirian</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="primary_identity_number" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Identitas
                                    </label>
                                    <input type="text" id="primary_identity_number" name="primary_identity_number" 
                                           value="{{ old('primary_identity_number') }}"
                                           placeholder="Nomor KTP/SIM/NPWP/dll"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="primary_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Telepon
                                    </label>
                                    <input type="tel" id="primary_phone" name="primary_phone" 
                                           value="{{ old('primary_phone') }}"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                </div>

                                <!-- Address -->
                                <div>
                                    <label for="primary_address" class="block text-sm font-medium text-gray-700 mb-1">
                                        Alamat
                                    </label>
                                    <textarea id="primary_address" name="primary_address" rows="2"
                                              placeholder="Alamat lengkap pihak yang bermasalah"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">{{ old('primary_address') }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4 bg-white border border-yellow-300 rounded-md p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="text-yellow-500">💡</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Info:</strong> Data pihak ini akan otomatis ditambahkan sebagai pihak terkait dalam kasus. 
                                            Anda dapat menambah pihak lain (penjamin, saksi, dll) setelah kasus dibuat.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Tambahan
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      placeholder="Catatan atau informasi tambahan (opsional)"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Progress Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="text-blue-400">ℹ️</span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Informasi Progress
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Kasus baru akan dimulai dengan <strong>Level 1: Inisiasi (25%)</strong></p>
                                        <p class="mt-1">
                                            Progress dapat diupdate setelah kasus dibuat melalui halaman detail kasus.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <a href="{{ route('legal-cases.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                💾 Simpan Kasus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>