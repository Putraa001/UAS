<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Jenis Kasus: {{ $caseType->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kode: {{ $caseType->code }} • Dibuat {{ $caseType->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('case-types.show', $caseType) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    👁️ Lihat
                </a>
                <a href="{{ route('case-types.index') }}" 
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

                    <form method="POST" action="{{ route('case-types.update', $caseType) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Kode Jenis Kasus <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="code" name="code" value="{{ old('code', $caseType->code) }}" required
                                       placeholder="Contoh: KB, KL, KP"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase">
                                <p class="mt-1 text-sm text-gray-500">
                                    Maksimal 10 huruf kapital, tanpa spasi. Digunakan untuk kode kasus (contoh: KB-2025-01-001)
                                </p>
                                @if($caseType->legalCases->count() > 0)
                                    <p class="mt-1 text-sm text-orange-600">
                                        ⚠️ Hati-hati mengubah kode, sudah digunakan di {{ $caseType->legalCases->count() }} kasus
                                    </p>
                                @endif
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                    Urutan Tampilan
                                </label>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $caseType->sort_order) }}" min="0"
                                       placeholder="0"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">
                                    Angka kecil akan tampil di atas
                                </p>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Jenis Kasus <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $caseType->name) }}" required
                                   placeholder="Contoh: Kredit Bermasalah, Kredit Lancar, dll"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi Jenis Kasus
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      placeholder="Jelaskan detail mengenai jenis kasus ini..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $caseType->description) }}</textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $caseType->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Status Aktif
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Jenis kasus yang aktif akan muncul dalam pilihan saat membuat kasus baru
                            </p>
                        </div>

                        <!-- Usage Info -->
                        @if($caseType->legalCases->count() > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="text-yellow-400">⚠️</span>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Jenis Kasus Sedang Digunakan
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Jenis kasus ini saat ini digunakan oleh <strong>{{ $caseType->legalCases->count() }} kasus hukum</strong>.</p>
                                            <p class="mt-1">Perubahan yang Anda lakukan akan mempengaruhi semua kasus yang menggunakan jenis ini.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Update Info -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">ℹ️ Info Update</h4>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div>Dibuat: {{ $caseType->created_at->format('d M Y H:i') }} oleh {{ $caseType->creator->name }}</div>
                                @if($caseType->updated_at != $caseType->created_at)
                                    <div>Update terakhir: {{ $caseType->updated_at->format('d M Y H:i') }} oleh {{ $caseType->updater->name ?? 'System' }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <a href="{{ route('case-types.show', $caseType) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ✅ Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto uppercase for code field
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z]/g, '');
        });
    </script>
</x-app-layout>