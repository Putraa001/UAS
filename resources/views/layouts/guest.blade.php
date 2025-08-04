<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sistem Manajemen Hukum - PT Tersenyum Abadi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- BKK Custom Theme -->
        <link rel="stylesheet" href="{{ asset('css/bkk-theme.css') }}"
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="text-center">
                <a href="/" class="flex flex-col items-center space-y-3">
                    <div class="text-8xl">😊</div>
                    <div class="text-blue-900 font-bold">
                        <div class="text-xl">Bank Tersenyum</div>
                        <div class="text-sm text-blue-700">PT Tersenyum Abadi</div>
                        <div class="text-xs text-gray-600 mt-1">Sistem Manajemen Hukum</div>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
