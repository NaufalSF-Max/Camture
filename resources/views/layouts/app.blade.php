<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') | {{ config('app.name', 'Camture') }}</title>
        <link rel="icon" href="{{ asset('images/logo-camture.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col bg-gray-100">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-camture-pink-bg shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-grow">
                {{ $slot }}
            </main>

            <footer class="bg-camture-green-dark text-camture-pink-bg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm">
                    <p>Hak Cipta &copy; {{ date('Y') }} Camture. All rights reserved.</p>
                    <div class="mt-2 space-x-4">
                        <a href="#" class="hover:underline">TnC</a>
                        <a href="#" class="hover:underline">Kebijakan Privasi</a>
                    </div>
                </div>
            </footer>
        </div>
        @stack('scripts')
    </body>
</html>