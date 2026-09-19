<x-guest-layout>
    <!-- Header / Branding Section -->
    <div class="mb-6 text-center">
        <div
            class="inline-flex items-center justify-center w-12 h-12 mb-3 rounded-xl bg-[#FCECD8] text-[#597928] ring-8 ring-[#FCECD8]/50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-[#6E3511]">Selamat Datang Kembali</h2>
        <p class="mt-1 text-sm text-gray-500">Masuk ke Portal Pembayaran SPP</p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
        @csrf

        <!-- Email Address -->
        <div>
            <x-form.input-label for="email" :value="__('Email')"
                class="text-xs font-semibold uppercase tracking-wider text-[#6E3511]/80" />
            <div class="relative mt-1.5 rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-form.text-input id="email"
                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 focus:border-[#597928] focus:ring-[#597928] text-sm transition duration-150 ease-in-out"
                    type="email" name="email" :value="old('email')" placeholder="nama@sekolah.sch.id" required autofocus
                    autocomplete="username" />
            </div>
            <x-form.input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-form.input-label for="password" :value="__('Kata Sandi')"
                class="text-xs font-semibold uppercase tracking-wider text-[#6E3511]/80" />
            <div class="relative mt-1.5 rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-form.text-input id="password"
                    class="block w-full pl-10 pr-10 py-2.5 rounded-lg border-gray-300 focus:border-[#597928] focus:ring-[#597928] text-sm transition duration-150 ease-in-out"
                    ::type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••" required autocomplete="current-password" />

                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#597928] focus:outline-none"
                    tabindex="-1">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-form.input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="w-4 h-4 rounded border-gray-300 text-[#597928] shadow-sm focus:ring-[#597928] transition duration-150"
                    name="remember">
                <span class="ms-2 text-gray-600 hover:text-[#6E3511] select-none">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="font-medium text-[#597928] hover:text-[#6E3511] focus:outline-none focus:underline transition duration-150"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa kata sandi?') }}
                </a>
            @endif
        </div>

        <!-- Primary Action Button -->
        <div>
            <x-button.primary-button
                class="w-full justify-center py-2.5 text-sm font-semibold tracking-wide rounded-lg shadow-md bg-[#597928] hover:bg-[#6E3511] focus:ring-[#597928] active:bg-[#6E3511] transition duration-150">
                {{ __('Masuk ke Akun') }}
            </x-button.primary-button>
        </div>

        <!-- Registration Link -->
        <div class="pt-2 text-center text-xs text-gray-500">
            {{ __('Belum memiliki akun?') }}
            <a href="{{ route('register') }}" class="font-semibold text-[#597928] hover:text-[#6E3511] hover:underline">
                {{ __('Daftar disini') }}
            </a>
        </div>
    </form>
</x-guest-layout>
