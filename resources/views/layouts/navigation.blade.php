<nav x-data="{ open: false }" class="bg-camture-green-dark border-b border-camture-green-light">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/logo-camture.png') }}" alt="Camture Logo" class="block h-14 w-auto">
                    </a>
                </div>
            </div>

            <!-- Navigation Links (Tengah) -->
            <div class="hidden sm:flex sm:items-center">
                <div class="space-x-8">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')" class="text-white">
                        Home
                    </x-nav-link>
                    <x-nav-link :href="route('layouts.select')" :active="request()->routeIs('layouts.select')" class="text-white">
                        Fitur
                    </x-nav-link>
                    <x-nav-link :href="route('photo.gallery')" :active="request()->routeIs('photo.gallery')" class="text-white">
                        Galeri
                    </x-nav-link>
                </div>
            </div>

            <!-- Tombol Kanan (Login/Register atau Dropdown Pengguna) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Route::has('login'))
                    @auth
                        <!-- Dropdown untuk pengguna yang sudah login -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                                    <div><span class="font-bold">{{ Auth::user()->name }}</span></div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="bg-camture-rose text-white hover:bg-camture-rose-hover focus:bg-camture-rose-hover">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <!-- Tombol untuk tamu -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="px-6 py-2 border border-white rounded-md font-semibold hover:bg-white hover:text-camture-green-dark transition duration-300 text-sm">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-camture-rose to-camture-peach text-white rounded-md font-semibold hover:opacity-90 transition duration-300 text-sm shadow-md">
                                    Daftar Gratis!
                                </a>
                            @endif
                        </div>
                    @endauth
                @endif
            </div>

            <!-- Hamburger (tetap sama) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    {{-- Responsive Navigation Menu (jika diperlukan, bisa disesuaikan juga) --}}
</nav>