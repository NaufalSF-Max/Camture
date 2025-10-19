<x-admin-layout>
    @section('title', 'Admin Dashboard')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {{-- Total Pengguna --}}
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $userCount }}</p>
                    </div>
                    <div class="bg-camture-pink-bg text-camture-rose p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-9-9"></path></svg>
                    </div>
                </div>

                {{-- Total Foto --}}
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Foto</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $photoCount }}</p>
                    </div>
                    <div class="bg-green-100 text-green-600 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l2-2a2 2 0 012.828 0l2 2m-4 5h.01M19 21v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2h14z"></path></svg>
                    </div>
                </div>
                
                {{-- Template Aktif --}}
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Template Aktif</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $activeTemplateCount }}</p>
                    </div>
                    <div class="bg-yellow-100 text-yellow-600 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- Tabel Template Terbaru --}}
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Template Terbaru Ditambahkan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 px-4">Nama Template</th>
                                <th class="py-2 px-4">Jumlah Slot</th>
                                <th class="py-2 px-4">Tanggal Dibuat</th>
                                <th class="py-2 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTemplates as $template)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 font-medium">{{ $template->name }}</td>
                                    <td class="py-3 px-4">{{ $template->capture_slots }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $template->created_at->format('d M Y') }}</td>
                                    <td class="py-3 px-4">
                                        @if ($template->is_active)
                                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Aktif</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada template yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>