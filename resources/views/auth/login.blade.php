<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Welcome Message -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-semibold text-blue-900 mb-2">Selamat Datang</h2>
        <p class="text-sm text-gray-600">Silakan masuk ke Sistem Manajemen Hukum</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                📧 Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Masukkan email Anda">
            @if($errors->get('email'))
                <div class="mt-2 text-sm text-red-600">
                    @foreach($errors->get('email') as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                🔒 Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Masukkan password Anda">
            @if($errors->get('password'))
                <div class="mt-2 text-sm text-red-600">
                    @foreach($errors->get('password') as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" 
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                Ingat saya
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                🚀 Masuk ke Sistem
            </button>
        </div>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="text-center">
                <a href="{{ route('password.request') }}" 
                   class="text-sm text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                    Lupa password?
                </a>
            </div>
        @endif
    </form>

    <!-- Footer Info -->
    <div class="mt-6 text-center">
        <div class="border-t border-gray-200 pt-4">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} PT Tersenyum Abadi
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Sistem Manajemen Hukum v1.0
            </p>
        </div>
    </div>
</x-guest-layout>
