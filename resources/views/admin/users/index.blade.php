<x-admin-layout>
    @section('title', 'Kelola Pengguna')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
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

                    {{-- ACTION BAR: Search & Export (Fitur Baru) --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                        {{-- 1. Fitur Searching --}}
                        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-1/2 flex">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau email pengguna..."
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

                        {{-- 2. Fitur Export Data --}}
                        <a href="{{ route('admin.users.export') }}"
                            class="flex items-center justify-center w-full md:w-auto bg-green-600 text-white px-5 py-2 rounded-md hover:bg-green-700 transition shadow-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Laporan (.xls)
                        </a>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-left">
                            <thead
                                class="bg-gray-50 border-b-2 border-gray-200 text-gray-600 uppercase text-xs tracking-wider">
                                <tr>
                                    {{-- KOLOM NAMA --}}
                                    <th
                                        class="py-3 px-4 font-semibold cursor-pointer hover:bg-gray-100 transition group">
                                        <a href="{{ route('admin.users.index', ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                                            class="flex items-center justify-between group-hover:text-gray-900">
                                            <span>Nama</span>

                                            {{-- Logika Ikon Sorting --}}
                                            @if(request('sort') == 'name')
                                                {{-- Jika Sedang Aktif: Tampilkan Panah Sesuai Arah --}}
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
                                                {{-- Jika Tidak Aktif: Tampilkan Ikon Netral (Abu-abu Pudar) --}}
                                                <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </th>

                                    {{-- KOLOM EMAIL --}}
                                    <th
                                        class="py-3 px-4 font-semibold cursor-pointer hover:bg-gray-100 transition group">
                                        <a href="{{ route('admin.users.index', ['sort' => 'email', 'direction' => request('sort') == 'email' && request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                                            class="flex items-center justify-between group-hover:text-gray-900">
                                            <span>Email</span>

                                            @if(request('sort') == 'email')
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

                                    {{-- KOLOM NON-SORTABLE --}}
                                    <th class="py-3 px-4 font-semibold text-center">Jml Foto</th>
                                    <th class="py-3 px-4 font-semibold text-center">Role</th>

                                    {{-- KOLOM TANGGAL BERGABUNG --}}
                                    <th
                                        class="py-3 px-4 font-semibold cursor-pointer hover:bg-gray-100 transition group">
                                        <a href="{{ route('admin.users.index', ['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                                            class="flex items-center justify-between group-hover:text-gray-900">
                                            <span>Bergabung</span>

                                            @if(request('sort') == 'created_at')
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

                                    <th class="py-3 px-4 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                                        {{-- Menampilkan jumlah foto (relasi withCount) --}}
                                        <td class="py-3 px-4 text-center">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                {{ $user->photos_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span
                                                class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $user->role === 'admin' ? 'bg-camture-rose text-white' : 'bg-gray-200 text-gray-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST"
                                                class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role"
                                                    class="border-gray-300 focus:border-camture-rose focus:ring-camture-rose rounded-md shadow-sm text-sm py-1">
                                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User
                                                    </option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="text-sm text-blue-600 hover:text-blue-900 font-medium hover:underline">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500">
                                            Data tidak ditemukan. Coba kata kunci lain.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>