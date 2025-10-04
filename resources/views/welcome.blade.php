<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Camture - Abadikan Momenmu!</title>

    {{-- KODE CDN DAN STYLE INLINE YANG BENTROK SUDAH DIHAPUS DARI SINI --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-camture-pink-bg font-sans">

    <div class="flex flex-col min-h-screen">

        <header class="bg-camture-green-dark text-white shadow-md sticky top-0 z-50">
            <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
                <a href="/" class="text-2xl font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Cam<span class="text-camture-rose">ture</span></span>
                </a>
        
                <ul class="hidden md:flex items-center space-x-8 font-medium text-sm">
                    <li><a href="#" class="hover:text-gray-300 transition duration-300">Home</a></li>
                    <li><a href="#" class="hover:text-gray-300 transition duration-300">Fitur</a></li>
                    <li><a href="{{ route('photo.gallery') }}" class="hover:text-gray-300 transition duration-300">Galeri</a></li>
                    <li><a href="#" class="hover:text-gray-300 transition duration-300">Paket</a></li>
                    <li><a href="#" class="hover:text-gray-300 transition duration-300">Hubungi Kami</a></li>
                </ul>
        
                @if (Route::has('login'))
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/layouts') }}" class="px-6 py-2 bg-white text-camture-green-dark rounded-md font-semibold hover:bg-gray-200 transition duration-300 text-sm shadow">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 border border-white rounded-md font-semibold hover:bg-white hover:text-camture-green-dark transition duration-300 text-sm">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-camture-rose to-camture-peach text-white rounded-md font-semibold hover:opacity-90 transition duration-300 text-sm shadow-md">
                                    Daftar Gratis!
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
                
                <div class="md:hidden">
                    <button class="text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                    </button>
                </div>
            </nav>
        </header>

        <main class="flex-grow">
            <section class="container mx-auto px-6 py-16 md:py-24">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="text-center md:text-left">
                        <h1 class="text-5xl md:text-6xl font-extrabold text-camture-green-dark leading-tight tracking-tight">
                            Camture: Abadikan Momenmu dengan Gaya!
                        </h1>
                        <p class="mt-4 text-gray-700 text-lg">
                            Website photobooth interaktif dengan efek visual unik. Ciptakan kenangan unikmu sekarang!
                        </p>
                        <div class="mt-8 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                            <a href="{{ route('layouts.select') }}" class="px-8 py-3 bg-camture-rose text-white font-semibold rounded-full shadow-lg hover:bg-camture-rose-hover transition duration-300 transform hover:scale-105">
                                Mulai Petualangan Fotomu!
                            </a>
                            <a href="{{ route('photo.gallery') }}" class="px-8 py-3 border-2 border-camture-rose text-camture-rose font-semibold rounded-full hover:bg-camture-rose hover:text-white transition duration-300">
                                Lihat Galeri
                            </a>
                        </div>
                    </div>
                    <div class="relative flex justify-center items-center h-full mt-10 md:mt-0">
                        <div class="bg-white p-3 pb-8 shadow-xl transform -rotate-12 transition hover:rotate-0 hover:scale-110 z-10">
                            <img src="{{ asset('images/home1.jpg') }}"  alt="Contoh Foto Photobooth 1" class="w-48 h-auto">
                            <p class="text-center mt-2 text-sm text-gray-600 font-medium">@Camture</p>
                        </div>
                        <div class="bg-white p-3 pb-8 shadow-2xl transform rotate-3 transition hover:rotate-0 hover:scale-110 z-20">
                            <img src="{{ asset('images/home2.jpg') }}"  alt="Contoh Foto Photobooth 2" class="w-56 h-auto">
                            <p class="text-center mt-2 text-sm text-gray-600 font-medium">@Camture</p>
                        </div>
                        <div class="bg-white p-3 pb-8 shadow-xl transform rotate-12 transition hover:rotate-0 hover:scale-110 z-10">
                            <img src="{{ asset('images/home3.jpg') }}"  alt="Contoh Foto Photobooth 3" class="w-48 h-auto">
                            <p class="text-center mt-2 text-sm text-gray-600 font-medium">@Camture</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white py-4 shadow-inner">
                <div class="container mx-auto text-center text-gray-600 font-medium">
                    <p class="text-sm md:text-base">
                        Berbagai Efek • Template Foto • Download • Share • Watermark
                    </p>
                </div>
            </section>
        </main>

        <footer class="bg-white py-6">
            <div class="container mx-auto px-6 flex justify-between items-center text-gray-600">
                <p class="font-bold text-lg text-camture-green-dark">Camture</p>
                <p class="text-sm">© {{ date('Y') }} Camture. All rights reserved.</p>
                <div></div>
            </div>
        </footer>
    </div>
</body>
</html>