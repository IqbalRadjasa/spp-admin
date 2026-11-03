<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-form.input-label for="email" :value="__('Email')" />
            <x-form.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)"
                required autofocus autocomplete="username" />
            <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ showPassword: false }">
            <x-form.input-label for="password" :value="__('Kata Sandi')" />
            <div class="relative mt-1">
                <x-form.text-input id="password" class="block w-full pr-10"
                    x-bind:type="showPassword ? 'text' : 'password'" name="password" required
                    autocomplete="new-password" />
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-500 hover:text-stone-700 focus:outline-none">
                    <i :class="showPassword ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-lg"></i>
                </button>
            </div>
            <x-form.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4" x-data="{ showConfirmPassword: false }">
            <x-form.input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />

            <div class="relative mt-1">
                <x-form.text-input id="password_confirmation" class="block w-full pr-10"
                    x-bind:type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required
                    autocomplete="new-password" />
                <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-500 hover:text-stone-700 focus:outline-none">
                    <i :class="showConfirmPassword ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-lg"></i>
                </button>
            </div>

            <x-form.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button.primary-button>
                {{ __('Reset Password') }}
            </x-button.primary-button>
        </div>
    </form>
</x-guest-layout>
