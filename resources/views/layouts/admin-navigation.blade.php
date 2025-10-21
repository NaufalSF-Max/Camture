{{-- Komponen navigasi/sidebar Admin, menggunakan Alpine.js untuk expand/collapse --}}
{{-- State 'open' defaultnya true di desktop (> 768px), false di mobile --}}
<div x-data="{ open: window.innerWidth > 768 }"
     {{-- Listener untuk resize window, otomatis collapse di mobile --}}
     @resize.window="if (window.innerWidth > 768) open = true; else open = false;"
     class="bg-camture-green-dark text-white flex-shrink-0 transition-all duration-300 ease-in-out z-20"
     :class="open ? 'w-64' : 'w-20'"> {{-- Lebar berubah berdasarkan state 'open' --}}

    {{-- Header Logo (menjadi tombol toggle di mobile <= 768px) --}}
    <div class="px-4 py-3 border-b border-camture-green-light">
        <div @click="if (window.innerWidth <= 768) open = !open" {{-- Toggle 'open' hanya di mobile --}}
             class="flex items-center cursor-pointer"
             :class="!open && 'justify-center'"> {{-- Center logo jika sidebar collapsed --}}
            <img src="{{ asset('images/logo-camture.png') }}" alt="Camture Logo" class="block h-15 w-auto flex-shrink-0">
            {{-- Nama aplikasi (dihapus agar lebih simpel, logo cukup) --}}
        </div>
    </div>

    {{-- Menu Links --}}
    <nav class="mt-4 px-2">
        <div class="space-y-2">
            {{-- Dashboard Link --}}
            {{-- Komponen Admin Nav Link (resources/views/components/admin-nav-link.blade.php) --}}
            <x-admin-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" title="Dashboard">
                {{-- Icon Dashboard --}}
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                {{-- Teks link (hanya tampil jika sidebar 'open') --}}
                <span class="ml-4 whitespace-nowrap" x-show="open">Dashboard</span>
            </x-admin-nav-link>

            {{-- Kelola Pengguna Link --}}
            <x-admin-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" title="Kelola Pengguna">
                {{-- Icon Users --}}
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-9-9"></path></svg>
                <span class="ml-4 whitespace-nowrap" x-show="open">Users</span>
            </x-admin-nav-link>

            {{-- Judul Grup Menu Templates (hanya tampil jika 'open') --}}
            <h3 class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider" x-show="open">
                Templates
            </h3>

            {{-- Manage Templates Link --}}
            {{-- Aktif jika route adalah index atau edit --}}
            <x-admin-nav-link :href="route('admin.templates.index')" :active="request()->routeIs('admin.templates.index') || request()->routeIs('admin.templates.edit')" title="Manage Templates">
                {{-- Icon Template --}}
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l2-2a2 2 0 012.828 0l2 2m-4 5h.01M19 21v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2h14z"></path></svg>
                <span class="ml-4 whitespace-nowrap" x-show="open">Manage Templates</span>
            </x-admin-nav-link>

            {{-- Add New Template Link --}}
            <x-admin-nav-link :href="route('admin.templates.create')" :active="request()->routeIs('admin.templates.create')" title="Add New Template">
                {{-- Icon Add --}}
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="ml-4 whitespace-nowrap" x-show="open">Add New Template</span>
            </x-admin-nav-link>
        </div>
    </nav>
</div>