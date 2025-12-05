<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'penAwan') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased bg-gradient-to-b from-white via-blue-50 to-blue-100">

        <div class="min-h-screen flex flex-col items-center justify-center p-6">

            <!-- (Logo removed) -->
            <!-- Empty space for breathing room -->
            <div class="mb-4"></div>

            <!-- Card Form -->
            <div class="w-full sm:max-w-md bg-white/80 backdrop-blur-xl shadow-xl 
                        border border-white/60 rounded-3xl p-8">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>
