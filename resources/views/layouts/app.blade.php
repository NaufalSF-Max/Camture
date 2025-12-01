<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- CSRF token untuk keamanan form --}}

    <meta http-equiv="Content-Security-Policy"
        content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">

    {{-- Judul halaman dinamis, default ke nama aplikasi jika tidak diset --}}
    <title>@yield('title') | {{ config('app.name', 'Camture') }}</title>
    <link rel="icon" href="{{ asset('images/logo-camture.png') }}" type="image/png"> {{-- Favicon --}}

    {{-- Font Google Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Library Fabric.js untuk editor stiker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    {{-- Vite untuk mengelola aset CSS dan JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    {{-- Container utama dengan flexbox untuk layout sticky footer --}}
    <div class="min-h-screen flex flex-col bg-gray-100">

        {{-- Memuat komponen navigasi utama --}}
        @include('layouts.navigation')

        {{-- Header halaman (jika ada slot 'header' yang diisi) --}}
        @isset($header)
            <header class="bg-camture-pink-bg shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }} {{-- Menampilkan konten slot 'header' --}}
                </div>
            </header>
        @endisset

        {{-- Konten utama halaman (akan diisi oleh view yang meng-extend layout ini) --}}
        <main class="flex-grow">
            {{ $slot }} {{-- Menampilkan konten utama dari view --}}
        </main>

        {{-- Memuat komponen footer --}}
        @include('layouts.partials.footer')

    </div>

    {{-- Stack untuk script tambahan dari view (jika ada) --}}
    @stack('scripts')
</body>

</html>