<x-app-layout>
    {{-- Slot header dikosongkan karena judul dipindah ke dalam konten --}}
    <x-slot name="header"></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-camture-pink-bg overflow-hidden shadow-xl sm:rounded-2xl p-8 text-center">

                <h3 class="text-3xl font-extrabold mb-6 text-camture-rose">Foto Kamu Sudah Jadi!</h3>

                <div class="max-w-md mx-auto border-4 border-camture-rose rounded-lg shadow-lg p-2 bg-white">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Hasil Foto Camture" class="w-full h-auto rounded-md">
                </div>

                <div class="mt-8 max-w-md mx-auto">
                    <form action="{{ route('photo.update_title', $photo) }}" method="POST" class="text-left">
                        @csrf
                        @method('PATCH')

                        <label for="title" class="block font-medium text-sm text-camture-green-dark">Beri Judul Fotomu</label>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" id="title" name="title" 
                                   class="block w-full border-camture-rose rounded-md shadow-sm focus:border-camture-rose focus:ring focus:ring-camture-rose focus:ring-opacity-50" 
                                   value="{{ old('title', $photo->title) }}"
                                   placeholder="Contoh: Liburan Seru!">
                            <button type="submit" class="px-4 py-2 bg-camture-green-dark text-white text-xs font-semibold uppercase rounded-md hover:bg-camture-green-light transition-colors">
                                Simpan
                            </button>
                        </div>
                        @if(session('title_success'))
                            <p class="text-sm text-camture-green-light mt-2">{{ session('title_success') }}</p>
                        @endif
                    </form>
                </div>
                
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ asset('storage/' . $photo->file_path) }}" download="camture-{{ Str::slug($photo->title ?? uniqid()) }}.jpg"
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-camture-peach to-camture-rose border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-camture-rose transition ease-in-out duration-150 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Foto
                    </a>
                    <a href="{{ route('layouts.select') }}"
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-camture-green-light border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-camture-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-camture-green-light transition ease-in-out duration-150 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Ambil Foto Baru
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>