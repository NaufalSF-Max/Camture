<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') | {{ config('app.name', 'Camture') }}</title>
        <link rel="icon" href="{{ asset('images/logo-camture.png') }}" type="image/png">

        {{-- Font Google Poppins --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        {{-- Vite assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Container utama dengan flexbox untuk layout sidebar --}}
        <div class="min-h-screen bg-gray-100 flex">

            {{-- Navigasi/Sidebar Admin --}}
            @include('layouts.admin-navigation')

            {{-- Konten Utama Admin --}}
            <div class="flex-1 flex flex-col">
                {{-- Header Admin (judul halaman & info user) --}}
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        {{-- Menampilkan slot header jika ada --}}
                        @isset($header)
                            {{ $header }}
                        @endisset
                        {{-- Informasi user dan tombol logout --}}
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="text-sm text-gray-500 hover:text-gray-700">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                </header>

                {{-- Konten utama halaman admin --}}
                <main class="flex-grow p-6">
                    {{ $slot }} {{-- Menampilkan konten dari view admin --}}
                </main>
            </div>
        </div>
        {{-- Stack untuk script tambahan --}}
        @stack('scripts')
    </body>
</html>