<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Galeri Foto Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">

                <h3 class="text-2xl font-bold mb-6 text-center">Koleksi Fotomu</h3>

                @if($photos->isEmpty())
                    <div class="text-center text-gray-500 py-16">
                        <p class="text-lg">Kamu belum memiliki foto.</p>
                        <a href="{{ route('layouts.select') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                            Ayo Buat Foto Pertamamu!
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                        @foreach ($photos as $photo)
                            <a href="{{ route('photo.show', $photo) }}" class="group block">
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden shadow group-hover:shadow-xl transition-shadow">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                         alt="Foto Camture tanggal {{ $photo->created_at->format('d M Y') }}" 
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $photos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>