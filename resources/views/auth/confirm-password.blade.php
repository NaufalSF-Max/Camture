<x-guest-layout>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Konfirmasi Password</h2>
            <p class="text-sm text-gray-600">
                {{ __('Ini adalah area aman. Mohon konfirmasi password Anda sebelum melanjutkan.') }}
            </p>
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="!font-bold !text-camture-green-dark" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center text-base">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>