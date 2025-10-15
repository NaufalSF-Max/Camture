<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center bg-cover bg-center pt-24 pb-12" style="background-image: url('{{ asset('images/auth-bg.png') }}')">
            
            {{-- Navigasi Universal --}}
            <div class="absolute top-0 w-full z-10"> {{-- Menambahkan z-10 untuk memastikan nav di atas --}}
                @include('layouts.navigation')
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/90 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-lg">
                <div class="flex justify-center mb-6">
                    <a href="/">
                        <img src="{{ asset('images/logo-camture.png') }}" class="w-auto h-20" alt="Camture Logo">
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>