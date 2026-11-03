<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu alamat email Anda dan kami akan mengirimkan tautan reset kata sandi melalui email yang memungkinkan Anda memilih kata sandi baru.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-form.input-label for="email" :value="__('Email')" />
            <x-form.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus />
            <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <x-button.primary-button>
                {{ __('Kirim Tautan Reset Kata Sandi') }}
            </x-button.primary-button>

            <x-link-button.secondary-link :href="route('login')" class="flex-1">
                {{ __('Kembali') }}
            </x-link-button.secondary-link>
        </div>
    </form>
</x-guest-layout>
