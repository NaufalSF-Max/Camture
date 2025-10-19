<x-admin-layout>
    @section('title', 'Manajemen Template')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Template
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="py-3 px-4 w-1/4">Preview</th>
                                    <th class="py-3 px-4">Nama Template</th>
                                    <th class="py-3 px-4 text-center">Jumlah Slot</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($templates as $template)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <img src="{{ asset('storage/' . $template->image_path) }}" alt="{{ $template->name }}" class="h-16 w-auto rounded-md object-cover border">
                                        </td>
                                        <td class="py-3 px-4 font-medium">{{ $template->name }}</td>
                                        <td class="py-3 px-4 text-center">{{ $template->capture_slots }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if ($template->is_active)
                                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Aktif</span>
                                            @else
                                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- Tombol Toggle Status --}}
                                                <form action="{{ route('admin.templates.toggleStatus', $template) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengubah status template ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-sm font-medium {{ $template->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                        {{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>

                                                <span class="text-gray-300">|</span>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.templates.edit', $template) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">Edit</a>

                                                <span class="text-gray-300">|</span>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus template ini akan menghapus semua foto yang terkait. Anda yakin?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500">
                                            Belum ada template yang dibuat. <a href="{{ route('admin.templates.create') }}" class="text-blue-600 hover:underline">Buat sekarang!</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>