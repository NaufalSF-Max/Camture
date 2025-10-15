<x-app-layout>
    @section('title', 'Layouts')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-camture-pink-bg overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <h3 class="text-3xl font-extrabold mb-2 text-camture-rose">Choose Your Layout</h3>
                    <p class="text-camture-green-dark mb-10">Pilih layout untuk sesi fotomu. Setiap layout memiliki jumlah pose yang berbeda.</p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        @forelse ($templates as $template)
                            <a href="{{ route('camture.show', $template) }}" class="group transition-all duration-300 ease-in-out">
                                <div class="bg-white rounded-2xl shadow-lg p-4 group-hover:shadow-2xl group-hover:scale-105 transform">
                                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4 border-4 border-white shadow-inner">
                                        {{-- INI BAGIAN YANG DIPERBAIKI --}}
                                        <img src="{{ asset('storage/' . $template->image_path) }}" 
                                             alt="{{ $template->name }}" 
                                             class="w-full h-full object-contain">
                                    </div>
                                    <h4 class="font-bold text-lg text-camture-green-dark">{{ $template->name }}</h4>
                                    <p class="text-sm text-camture-green-light font-semibold">{{ $template->capture_slots }} foto</p>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-16">
                                <p class="text-lg text-camture-rose">Belum ada template yang tersedia.</p>
                                <p class="text-sm text-camture-green-dark mt-2">Silakan hubungi admin untuk menambahkan template baru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>