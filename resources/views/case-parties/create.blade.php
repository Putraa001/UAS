<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Pihak Terkait
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kasus: {{ $legalCase->case_code }} - {{ $legalCase->title }}
                </p>
            </div>
            <a href="{{ route('case-parties.index', $legalCase) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                « Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('case-parties.store', $legalCase) }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <!-- Left Column - Basic Info -->
                            <div class="space-y-6">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-blue-900 mb-4">👤 Informasi Dasar</h3>
                                    
                                    <!-- Party Type -->
                                    <div class="mb-4">
                                        <label for="party_type" class="block text-sm font-medium text-gray-700 mb-2">
                                            🏷️ Tipe Pihak *
                                        </label>
                                        <select id="party_type" name="party_type" required 
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">-- Pilih Tipe Pihak --</option>
                                            @foreach(\App\Models\CaseParty::PARTY_TYPES as $key => $label)
                                                <option value="{{ $key }}" {{ old('party_type') === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('party_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Name -->
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            👤 Nama Lengkap *
                                        </label>
                                        <input id="name" name="name" type="text" required 
                                               value="{{ old('name') }}"
                                               placeholder="Masukkan nama lengkap"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Identity Type & Number -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="identity_type" class="block text-sm font-medium text-gray-700 mb-2">
                                                🪪 Jenis ID *
                                            </label>
                                            <select id="identity_type" name="identity_type" required 
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @foreach(\App\Models\CaseParty::IDENTITY_TYPES as $key => $label)
                                                    <option value="{{ $key }}" {{ old('identity_type') === $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('identity_type')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="identity_number" class="block text-sm font-medium text-gray-700 mb-2">
                                                🔢 Nomor ID
                                            </label>
                                            <input id="identity_number" name="identity_number" type="text" 
                                                   value="{{ old('identity_number') }}"
                                                   placeholder="Nomor identitas"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error('identity_number')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                            📊 Status *
                                        </label>
                                        <select id="status" name="status" required 
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @foreach(\App\Models\CaseParty::STATUS_OPTIONS as $key => $label)
                                                <option value="{{ $key }}" {{ old('status', 'aktif') === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Personal Data -->
                                <div class="bg-green-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-green-900 mb-4">📋 Data Personal</h3>
                                    
                                    <!-- Gender & Birth -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                                ⚧️ Gender
                                            </label>
                                            <select id="gender" name="gender" 
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                                <option value="">-- Pilih --</option>
                                                @foreach(\App\Models\CaseParty::GENDER_OPTIONS as $key => $label)
                                                    <option value="{{ $key }}" {{ old('gender') === $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                                📅 Tanggal Lahir
                                            </label>
                                            <input id="date_of_birth" name="date_of_birth" type="date" 
                                                   value="{{ old('date_of_birth') }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                            🏙️ Tempat Lahir
                                        </label>
                                        <input id="place_of_birth" name="place_of_birth" type="text" 
                                               value="{{ old('place_of_birth') }}"
                                               placeholder="Kota/Kabupaten kelahiran"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">
                                                💑 Status Pernikahan
                                            </label>
                                            <select id="marital_status" name="marital_status" 
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                                <option value="">-- Pilih --</option>
                                                @foreach(\App\Models\CaseParty::MARITAL_STATUS_OPTIONS as $key => $label)
                                                    <option value="{{ $key }}" {{ old('marital_status') === $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                                                💼 Pekerjaan
                                            </label>
                                            <input id="occupation" name="occupation" type="text" 
                                                   value="{{ old('occupation') }}"
                                                   placeholder="Pekerjaan/profesi"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Middle Column - Address & Contact -->
                            <div class="space-y-6">
                                <!-- Address -->
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-yellow-900 mb-4">📍 Alamat & Lokasi</h3>
                                    
                                    <div class="mb-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                            🏠 Alamat Lengkap *
                                        </label>
                                        <textarea id="address" name="address" rows="3" required 
                                                  placeholder="Jalan, Nomor, RT/RW, dll"
                                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring-yellow-500">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="village" class="block text-sm font-medium text-gray-700 mb-2">
                                                🏘️ Desa/Kelurahan
                                            </label>
                                            <input id="village" name="village" type="text" 
                                                   value="{{ old('village') }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        </div>
                                        <div>
                                            <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                                                🏛️ Kecamatan
                                            </label>
                                            <input id="district" name="district" type="text" 
                                                   value="{{ old('district') }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                                🏙️ Kota/Kabupaten
                                            </label>
                                            <input id="city" name="city" type="text" 
                                                   value="{{ old('city') }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        </div>
                                        <div>
                                            <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                                                🗺️ Provinsi
                                            </label>
                                            <input id="province" name="province" type="text" 
                                                   value="{{ old('province') }}"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact -->
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-purple-900 mb-4">📞 Kontak</h3>
                                    
                                    <div class="mb-4">
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            📱 Nomor Telepon
                                        </label>
                                        <input id="phone" name="phone" type="tel" 
                                               value="{{ old('phone') }}"
                                               placeholder="08xxxxxxxxxx"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>

                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            📧 Email
                                        </label>
                                        <input id="email" name="email" type="email" 
                                               value="{{ old('email') }}"
                                               placeholder="email@example.com"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">
                                                🚨 Kontak Darurat
                                            </label>
                                            <input id="emergency_contact" name="emergency_contact" type="text" 
                                                   value="{{ old('emergency_contact') }}"
                                                   placeholder="Nama kontak darurat"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label for="emergency_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                                📞 No. Kontak Darurat
                                            </label>
                                            <input id="emergency_phone" name="emergency_phone" type="tel" 
                                                   value="{{ old('emergency_phone') }}"
                                                   placeholder="08xxxxxxxxxx"
                                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Financial & Notes -->
                            <div class="space-y-6">
                                <!-- Financial Data -->
                                <div class="bg-red-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-red-900 mb-4">💰 Data Keuangan</h3>
                                    <p class="text-xs text-gray-600 mb-4">Khusus untuk debitor/penjamin</p>
                                    
                                    <div class="mb-4">
                                        <label for="monthly_income" class="block text-sm font-medium text-gray-700 mb-2">
                                            💵 Penghasilan Bulanan (Rp)
                                        </label>
                                        <input id="monthly_income" name="monthly_income" type="number" min="0" step="1000"
                                               value="{{ old('monthly_income') }}"
                                               placeholder="0"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500">
                                    </div>

                                    <div class="mb-4">
                                        <label for="debt_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                            📉 Jumlah Hutang (Rp)
                                        </label>
                                        <input id="debt_amount" name="debt_amount" type="number" min="0" step="1000"
                                               value="{{ old('debt_amount') }}"
                                               placeholder="0"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500">
                                    </div>

                                    <div class="mb-4">
                                        <label for="collateral_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            🏦 Deskripsi Jaminan
                                        </label>
                                        <textarea id="collateral_description" name="collateral_description" rows="3" 
                                                  placeholder="Jenis dan detail jaminan"
                                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('collateral_description') }}</textarea>
                                    </div>

                                    <div>
                                        <label for="collateral_value" class="block text-sm font-medium text-gray-700 mb-2">
                                            💎 Nilai Jaminan (Rp)
                                        </label>
                                        <input id="collateral_value" name="collateral_value" type="number" min="0" step="1000"
                                               value="{{ old('collateral_value') }}"
                                               placeholder="0"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500">
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">📝 Catatan</h3>
                                    
                                    <div class="mb-4">
                                        <label for="education" class="block text-sm font-medium text-gray-700 mb-2">
                                            🎓 Pendidikan
                                        </label>
                                        <input id="education" name="education" type="text" 
                                               value="{{ old('education') }}"
                                               placeholder="Pendidikan terakhir"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            💬 Catatan Tambahan
                                        </label>
                                        <textarea id="notes" name="notes" rows="4" 
                                                  placeholder="Catatan atau informasi tambahan lainnya"
                                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-gray-500 focus:ring-gray-500">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('case-parties.index', $legalCase) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                ❌ Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                ✅ Simpan Data Pihak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>