{{-- Halaman utama/landing page --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Camture - Abadikan Momenmu!</title>
    <link rel="icon" type="image/png" href="../../public/images/favicon.png">

    {{-- Font Google Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-camture-pink-bg font-sans">

    {{-- Layout flexbox untuk sticky footer --}}
    <div class="flex flex-col min-h-screen">

        {{-- Memuat komponen navigasi --}}
        @include('layouts.navigation')

        {{-- Konten utama halaman welcome --}}
        <main class="flex-grow">
            {{-- Hero Section --}}
            <section class="container mx-auto px-6 py-16 md:py-24">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    {{-- Kolom Kiri: Teks & Tombol CTA --}}
                    <div class="text-center md:text-left">
                        <h1
                            class="text-5xl md:text-6xl font-extrabold text-camture-green-dark leading-tight tracking-tight">
                            Camture: Abadikan Momenmu dengan Gaya!
                        </h1>
                        <p class="mt-4 text-gray-700 text-lg">
                            Website photobooth interaktif dengan efek visual unik. Ciptakan kenangan unikmu sekarang!
                        </p>
                        {{-- Tombol Call to Action (CTA) --}}
                        <div class="mt-8 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                            {{-- Tombol Mulai (ke pemilihan template) --}}
                            <a href="{{ route('template.select') }}"
                                class="px-8 py-3 bg-camture-rose text-white font-semibold rounded-full shadow-lg hover:bg-camture-rose-hover transition duration-300 transform hover:scale-105">
                                Mulai Petualangan Fotomu!
                            </a>
                            {{-- Tombol Lihat Galeri --}}
                            <a href="{{ route('photo.gallery') }}"
                                class="px-8 py-3 border-2 border-camture-rose text-camture-rose font-semibold rounded-full hover:bg-camture-rose hover:text-white transition duration-300">
                                Lihat Galeri
                            </a>
                        </div>
                    </div>
                    {{-- Kolom Kanan: Gambar Ilustrasi (strip foto) --}}
                    <div class="relative flex justify-center items-center h-full mt-10 md:mt-0">
                        {{-- Tiga gambar strip foto dengan efek tumpuk & rotate --}}
                        <div
                            class="bg-white p-3 pb-8 shadow-xl transform -rotate-12 transition hover:rotate-0 hover:scale-110 z-10">
                            <img src="{{ asset('images/home1.jpg') }}" alt="Contoh Foto Photobooth 1"
                                class="w-48 h-auto">
                            <p class="text-center mt-2 text-sm text-gray-600 font-medium">@Camture</p>
                        </div>
                        <div
                            class="bg-white p-3 pb-8 shadow-2xl transform rotate-3 transition hover:rotate-0 hover:scale-110 z-20">
                            <img src="{{ asset('images/home2.jpg') }}" alt="Contoh Foto Photobooth 2"
                                class="w-56 h-auto">
                            <p class="text-center mt-2 text-sm text-gray-600 font-medium">@Camture</p>
                        </div>
                        <div
                            class="bg-white p-3 pb-8 shadow-xl transform rotate-12 transition hover:rotate-0 hover:scale-110 z-10">
                            <img src="{{ asset('images/home3.jpg') }}" alt="Contoh Foto Photobooth 3"
                                class="w-48 h-auto">
                            <p class="text-center mt-2 text-sm text-gray-600 font-medium">@Camture</p>
                        </div>
                    </div>
                </div>
            </section>
            {{-- Bagian fitur singkat --}}
            <section class="bg-white py-4 shadow-inner">
                <div class="container mx-auto text-center text-gray-600 font-medium">
                    <p class="text-sm md:text-base">
                        Berbagai Efek • Template Foto • Download • Share • Watermark
                    </p>
                </div>
            </section>
        </main>

        {{-- Memuat komponen footer --}}
        @include('layouts.partials.footer')

    </div>
</body>

</html>