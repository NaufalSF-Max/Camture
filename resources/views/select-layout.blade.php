{{-- Menggunakan layout utama aplikasi --}}
<x-app-layout>
    {{-- Menetapkan judul halaman --}}
    @section('title', 'Pilih Layout')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card utama --}}
            <div class="bg-camture-pink-bg overflow-hidden shadow-xl sm:rounded-2xl p-8">
                {{-- Judul halaman --}}
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-camture-rose">Pilih Layout Favoritmu</h2>
                    <p class="mt-2 text-md text-camture-green-dark">Klik pada salah satu template untuk memulai sesi fotomu!</p>
                </div>

                {{-- Cek jika tidak ada template yang aktif --}}
                @if($templates->isEmpty())
                    {{-- Tampilan "Empty State" --}}
                    <div class="text-center py-16">
                         <p class="text-lg font-semibold text-camture-green-dark">Oops! Belum ada template yang tersedia.</p>
                         <p class="text-sm text-camture-green-light">Admin sedang menyiapkannya, silakan kembali lagi nanti.</p>
                    </div>
                {{-- Jika ada template --}}
                @else
                    {{-- Grid untuk menampilkan template --}}
                    <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                        {{-- Loop melalui setiap template yang aktif --}}
                        @foreach ($templates as $template)
                            {{-- Link ke halaman photobooth dengan template terpilih --}}
                            <a href="{{ route('camture.show', $template) }}" class="group block text-center">
                                {{-- Card template --}}
                                <div class="aspect-[3/4] bg-white p-2 rounded-lg overflow-hidden shadow-lg group-hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-2 border-2 border-transparent group-hover:border-camture-rose">
                                    {{-- Gambar preview template --}}
                                    <img src="{{ asset('storage/' . $template->image_path) }}"
                                         alt="{{ $template->name }}"
                                         class="w-full h-full object-contain rounded-md">
                                </div>
                                {{-- Nama template --}}
                                <p class="mt-3 text-sm font-semibold text-camture-green-dark group-hover:text-camture-rose transition-colors truncate">
                                    {{ $template->name }}
                                </p>
                                {{-- Jumlah slot --}}
                                <p class="text-xs text-camture-green-light">
                                    {{ $template->capture_slots }} Slot Foto
                                </p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>