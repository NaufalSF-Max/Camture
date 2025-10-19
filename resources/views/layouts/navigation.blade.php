<nav x-data="{ open: false }" class="bg-camture-green-dark border-b border-camture-green-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- PERUBAHAN 1: Menambahkan kelas 'relative' --}}
        <div class="flex justify-between h-16 relative">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/logo-camture.png') }}" alt="Camture Logo" class="block h-12 w-auto">
                    </a>
                </div>
            </div>

            {{-- PERUBAHAN 2: Mengubah kelas untuk pemosisian absolut di tengah --}}
            <div class="hidden sm:flex sm:items-center sm:absolute sm:left-1/2 sm:top-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2">
                <div class="space-x-8">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')" class="text-white hover:text-gray-300">
                        Home
                    </x-nav-link>
                    <x-nav-link :href="route('template.select')" :active="request()->routeIs('template.select')" class="text-white hover:text-gray-300">
                        {{ __('Photobooth') }}
                    </x-nav-link>
                    <x-nav-link :href="route('photo.gallery')" :active="request()->routeIs('photo.gallery')" class="text-white hover:text-gray-300">
                        {{ __('Galeri') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
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
                                {{ __('Profil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="px-6 py-2 border border-white rounded-md font-semibold text-white hover:bg-white hover:text-camture-green-dark transition duration-300 text-sm">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-camture-rose to-camture-peach text-white rounded-md font-semibold hover:opacity-90 transition duration-300 text-sm shadow-md">
                                Daftar Gratis!
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-200 hover:bg-camture-green-light focus:outline-none focus:bg-camture-green-light focus:text-gray-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- Bagian ini tidak diubah dan akan tetap berfungsi seperti sebelumnya --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                Home
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('template.select')" :active="request()->routeIs('template.select')">
                {{ __('Photobooth') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('photo.gallery')" :active="request()->routeIs('photo.gallery')">
                {{ __('Galeri') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-camture-green-light">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        Login
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            Daftar
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>