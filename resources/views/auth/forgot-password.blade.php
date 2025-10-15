<x-guest-layout>
    @section('title', 'Forgot Password')
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Lupa Password?</h2>
            <p class="text-sm text-gray-600">
                {{ __('Tidak masalah. Cukup masukkan alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.') }}
            </p>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="!font-bold !text-camture-green-dark" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center text-base">
                {{ __('Kirim Link Reset Password') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a class="text-sm font-medium text-camture-rose hover:underline" href="{{ route('login') }}">
                {{ __('Kembali ke Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>