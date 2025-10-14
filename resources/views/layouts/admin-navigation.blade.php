<div class="w-64 bg-camture-green-dark text-white flex-shrink-0">
    <div class="p-6">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center">
            <img src="{{ asset('images/logo-camture.png') }}" alt="Camture Logo" class="block h-17 w-auto">
        </a>
    </div>
    <nav class="mt-6 px-4">
        <div class="space-y-1">
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </x-nav-link>

            {{-- Judul untuk grup menu --}}
            <h3 class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Templates
            </h3>

            {{-- PERUBAHAN: Mengganti nama dan menambahkan link baru --}}
            <x-nav-link :href="route('admin.templates.index')" :active="request()->routeIs('admin.templates.index') || request()->routeIs('admin.templates.edit')" class="text-white">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l2-2a2 2 0 012.828 0l2 2m-4 5h.01M19 21v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2h14z"></path></svg>
                Manage Templates
            </x-nav-link>
            
            <x-nav-link :href="route('admin.templates.create')" :active="request()->routeIs('admin.templates.create')" class="text-white">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Add New Template
            </x-nav-link>
        </div>
        
        {{-- Link navigasi admin lainnya bisa ditambahkan di sini --}}
    </nav>
</div>