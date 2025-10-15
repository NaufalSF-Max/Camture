<x-app-layout>
    @section('title', 'Gallery')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Main card container dengan background pink --}}
            <div class="bg-camture-pink-bg overflow-hidden shadow-xl sm:rounded-2xl p-8">

                <h3 class="text-3xl font-extrabold mb-8 text-center text-camture-rose">Galeri Foto Saya</h3>

                @if($photos->isEmpty())
                    {{-- Tampilan jika galeri kosong (Empty State) --}}
                    <div class="text-center py-16">
                        <svg class="mx-auto h-16 w-16 text-camture-rose opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <p class="text-lg mt-4 font-semibold text-camture-green-dark">Kamu belum memiliki foto.</p>
                        <p class="text-sm text-camture-green-light">Semua hasil jepretanmu akan muncul di sini.</p>
                        <a href="{{ route('layouts.select') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-camture-peach to-camture-rose text-white font-semibold rounded-lg shadow-md hover:opacity-90 transition-transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Ayo Buat Foto Pertamamu!
                        </a>
                    </div>
                @else
                    {{-- Tampilan Grid jika ada foto --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                        @foreach ($photos as $photo)
                            <a href="{{ route('photo.show', $photo) }}" class="group block text-center">
                                {{-- Card untuk setiap foto --}}
                                <div class="aspect-square bg-white p-2 rounded-lg overflow-hidden shadow-lg group-hover:shadow-2xl transition-all duration-300 border border-camture-rose border-opacity-30">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                         alt="{{ $photo->title ?? 'Foto Camture tanggal ' . $photo->created_at->format('d M Y') }}" 
                                         class="w-full h-full object-contain rounded-md group-hover:scale-105 transition-transform duration-300">
                                </div>
                                {{-- Judul dan Tanggal di bawah foto --}}
                                <p class="mt-2 text-sm font-semibold text-camture-green-dark group-hover:text-camture-rose transition-colors truncate">
                                    {{ $photo->title ?: 'Foto Tanpa Judul' }}
                                </p>
                                <p class="text-xs text-camture-green-light">
                                    {{ $photo->created_at->format('d M Y') }}
                                </p>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $photos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>