<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
                <p class="text-3xl font-bold text-gray-800">1,234</p> {{-- Ganti dengan data dinamis --}}
            </div>
            <div class="bg-camture-pink-bg text-camture-rose p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-9-9"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Foto</p>
                <p class="text-3xl font-bold text-gray-800">5,678</p> {{-- Ganti dengan data dinamis --}}
            </div>
            <div class="bg-green-100 text-green-600 p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l2-2a2 2 0 012.828 0l2 2m-4 5h.01M19 21v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2h14z"></path></svg>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Template Aktif</p>
                <p class="text-3xl font-bold text-gray-800">12</p> {{-- Ganti dengan data dinamis --}}
            </div>
            <div class="bg-yellow-100 text-yellow-600 p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Kunjungan</p>
                <p class="text-3xl font-bold text-gray-800">98</p> {{-- Ganti dengan data dinamis --}}
            </div>
            <div class="bg-red-100 text-red-600 p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    {{-- Bagian Tambahan: Aktivitas Terkini --}}
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
                    {{-- Ganti dengan loop data dinamis dari controller --}}
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium">Classic Booth</td>
                        <td class="py-3 px-4">4</td>
                        <td class="py-3 px-4 text-gray-600">12 Okt 2025</td>
                        <td class="py-3 px-4"><span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Aktif</span></td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium">Funky Polaroid</td>
                        <td class="py-3 px-4">1</td>
                        <td class="py-3 px-4 text-gray-600">10 Okt 2025</td>
                        <td class="py-3 px-4"><span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Aktif</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium">Minimalist Single</td>
                        <td class="py-3 px-4">1</td>
                        <td class="py-3 px-4 text-gray-600">09 Okt 2025</td>
                        <td class="py-3 px-4"><span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Nonaktif</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>