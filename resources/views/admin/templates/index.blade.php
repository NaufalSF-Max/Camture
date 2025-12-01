<x-admin-layout>
    @section('title', 'Manajemen Template')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Template
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ACTION BAR: Search & Tombol Tambah --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        {{-- 1. Search Bar --}}
                        <form action="{{ route('admin.templates.index') }}" method="GET" class="w-full md:w-1/2 flex">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama template..."
                                class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-camture-rose focus:ring-camture-rose sm:text-sm">
                            <button type="submit"
                                class="bg-gray-800 text-white px-4 py-2 rounded-r-md hover:bg-gray-700 transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>

                        {{-- 2. Tombol Tambah Template (Selalu Muncul) --}}
                        <a href="{{ route('admin.templates.create') }}"
                            class="flex items-center justify-center w-full md:w-auto bg-camture-rose text-white px-5 py-2 rounded-md hover:bg-camture-rose-hover transition shadow-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Template Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-left">
                            <thead
                                class="bg-gray-50 border-b-2 border-gray-200 text-gray-600 uppercase text-xs tracking-wider">
                                <tr>
                                    {{-- Kolom Preview (Tidak Sortable) --}}
                                    <th class="py-3 px-4 w-1/4">Preview</th>

                                    {{-- Kolom Nama (Sortable) --}}
                                    <th
                                        class="py-3 px-4 font-semibold cursor-pointer hover:bg-gray-100 transition group">
                                        <a href="{{ route('admin.templates.index', ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                                            class="flex items-center justify-between group-hover:text-gray-900">
                                            <span>Nama Template</span>
                                            @if(request('sort') == 'name')
                                                @if(request('direction') == 'asc')
                                                    <svg class="w-4 h-4 text-camture-rose" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-camture-rose" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </th>

                                    {{-- Kolom Slots (Sortable) --}}
                                    <th
                                        class="py-3 px-4 text-center font-semibold cursor-pointer hover:bg-gray-100 transition group">
                                        <a href="{{ route('admin.templates.index', ['sort' => 'capture_slots', 'direction' => request('sort') == 'capture_slots' && request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                                            class="flex items-center justify-center group-hover:text-gray-900 gap-1">
                                            <span>Jml Slot</span>
                                            @if(request('sort') == 'capture_slots')
                                                <span
                                                    class="text-camture-rose">{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                            @else
                                                <span class="text-gray-300 group-hover:text-gray-500">⇅</span>
                                            @endif
                                        </a>
                                    </th>

                                    {{-- Kolom Status (Sortable) --}}
                                    <th
                                        class="py-3 px-4 text-center font-semibold cursor-pointer hover:bg-gray-100 transition group">
                                        <a href="{{ route('admin.templates.index', ['sort' => 'is_active', 'direction' => request('sort') == 'is_active' && request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                                            class="flex items-center justify-center group-hover:text-gray-900 gap-1">
                                            <span>Status</span>
                                            @if(request('sort') == 'is_active')
                                                <span
                                                    class="text-camture-rose">{{ request('direction') == 'asc' ? '▲' : '▼' }}</span>
                                            @else
                                                <span class="text-gray-300 group-hover:text-gray-500">⇅</span>
                                            @endif
                                        </a>
                                    </th>

                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($templates as $template)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4">
                                            <div
                                                class="h-16 w-24 rounded-md overflow-hidden border bg-gray-100 relative group">
                                                @if($template->image_path && file_exists(public_path('storage/' . $template->image_path)))
                                                    <img src="{{ asset('storage/' . $template->image_path) }}"
                                                        alt="{{ $template->name }}" class="h-full w-full object-contain">
                                                @else
                                                    {{-- Tampilan jika gambar rusak/hilang --}}
                                                    <div
                                                        class="flex items-center justify-center h-full w-full bg-gray-200 text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $template->name }}</td>
                                        <td class="py-3 px-4 text-center">{{ $template->capture_slots }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if ($template->is_active)
                                                <span
                                                    class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Aktif</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- Toggle --}}
                                                <form action="{{ route('admin.templates.toggleStatus', $template) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        title="{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                        class="{{ $template->is_active ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                {{-- Edit --}}
                                                <a href="{{ route('admin.templates.edit', $template) }}"
                                                    class="text-blue-600 hover:text-blue-800" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route('admin.templates.destroy', $template) }}"
                                                    method="POST" onsubmit="return confirm('Hapus template ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800"
                                                        title="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500 bg-gray-50">
                                            Tidak ditemukan template yang cocok.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $templates->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>