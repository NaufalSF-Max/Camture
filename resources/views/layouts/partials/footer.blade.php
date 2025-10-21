{{-- Komponen footer sederhana --}}
<footer class="bg-camture-green-dark text-camture-pink-bg">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm">
        {{-- Copyright dengan tahun dinamis --}}
        © {{ date('Y') }} Camture. All rights reserved. |
        {{-- Link ke halaman Syarat & Ketentuan --}}
        <a href="{{ route('terms') }}" class="hover:underline">Syarat & Ketentuan</a>
    </div>
</footer>