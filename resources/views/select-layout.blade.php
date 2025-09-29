<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pilih Layout Foto Anda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-bold mb-2">Choose Your Layout</h3>
                    <p class="text-gray-600 mb-8">Pilih layout untuk sesi fotomu. Setiap layout memiliki jumlah pose yang berbeda.</p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        @forelse ($templates as $template)
                            <a href="{{ route('camture.show', $template) }}" class="group">
                                <div class="bg-gray-100 rounded-lg shadow-md p-4 group-hover:shadow-xl group-hover:scale-105 transition-transform">
                                    <img src="{{ asset('storage/' . $template->image_path) }}" 
                                         alt="{{ $template->name }}" 
                                         class="w-full h-auto rounded-md mb-4 aspect-[3/4] object-contain">
                                    <h4 class="font-bold">{{ $template->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $template->capture_slots }} foto</p>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-full text-gray-500">Belum ada template yang tersedia. Silakan hubungi admin.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>