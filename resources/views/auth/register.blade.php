<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang!</h2>
            <p class="text-gray-600">Daftar untuk membuat akun baru Anda.</p>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" class="!font-bold !text-camture-green-dark" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="!font-bold !text-camture-green-dark" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="!font-bold !text-camture-green-dark" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="!font-bold !text-camture-green-dark" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center text-base">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        {{-- Bagian "Sign up with Google" telah dihapus --}}

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a class="font-bold text-camture-rose hover:underline" href="{{ route('login') }}">
                    {{ __('Log in') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>