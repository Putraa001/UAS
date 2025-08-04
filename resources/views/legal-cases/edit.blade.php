<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Kasus: {{ $legalCase->case_code }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $legalCase->case_type_name }} • Progress: {{ $legalCase->progress_level_name }} ({{ $legalCase->progress_percentage }}%)
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('legal-cases.show', $legalCase) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    👁️ Lihat Detail
                </a>
                <a href="{{ route('legal-cases.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    « Kembali
                </a>
            </div>
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

                    <form method="POST" action="{{ route('legal-cases.update', $legalCase) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Case Code (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Kasus
                            </label>
                            <input type="text" value="{{ $legalCase->case_code }}" readonly
                                   class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm cursor-not-allowed">
                            <p class="mt-1 text-sm text-gray-500">
                                Kode kasus tidak dapat diubah setelah dibuat
                            </p>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Judul Kasus <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $legalCase->title) }}" required
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
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $legalCase->description) }}</textarea>
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
                                        <option value="{{ $user->id }}" {{ old('assigned_to', $legalCase->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ ucfirst($user->role) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prioritas
                                </label>
                                <select id="priority" name="priority"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach(\App\Models\LegalCase::PRIORITY_OPTIONS as $key => $name)
                                        <option value="{{ $key }}" {{ old('priority', $legalCase->priority) == $key ? 'selected' : '' }}>
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
                                <input type="date" id="due_date" name="due_date" 
                                       value="{{ old('due_date', $legalCase->due_date?->format('Y-m-d')) }}" 
                                       min="{{ now()->format('Y-m-d') }}"
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
                                       value="{{ old('estimated_resolution_days', $legalCase->estimated_resolution_days) }}" 
                                       min="1" max="365" placeholder="Contoh: 30"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">
                                    Perkiraan jumlah hari untuk menyelesaikan kasus
                                </p>
                            </div>
                        </div>

                        <!-- Status (Admin Only) -->
                        @if(auth()->user()->role === 'admin')
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status Kasus
                                </label>
                                <select id="status" name="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach(\App\Models\LegalCase::STATUS_OPTIONS as $key => $name)
                                        <option value="{{ $key }}" {{ old('status', $legalCase->status) == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    Hanya admin yang dapat mengubah status kasus
                                </p>
                            </div>
                        @endif

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Tambahan
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      placeholder="Catatan atau informasi tambahan (opsional)"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $legalCase->notes) }}</textarea>
                        </div>

                        <!-- Current Progress Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="text-blue-400">ℹ️</span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Progress Saat Ini
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p><strong>{{ $legalCase->progress_level_name }}</strong> ({{ $legalCase->progress_percentage }}%)</p>
                                        <p class="mt-1">
                                            Untuk mengubah progress, gunakan form "Update Progress" di halaman detail kasus.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <a href="{{ route('legal-cases.show', $legalCase) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                💾 Update Kasus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>