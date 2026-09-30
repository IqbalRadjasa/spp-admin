<x-guest-layout>
    <div class="max-w-md mx-auto my-10 bg-white p-6 rounded-xl border border-stone-200 shadow-sm">
        <h2 class="text-lg font-bold text-stone-800">Ubah Password Pertama Kali</h2>
        <p class="text-xs text-stone-500 mb-6">Demi keamanan akun, Anda diwajibkan mengganti password sementara yang
            diberikan.</p>

        <form method="POST" action="{{ route('password.first_change.update') }}">
            @csrf

            <!-- Current Temporary Password -->
            <div class="mb-4" x-data="{ show: false }">
                <x-form.input-label for="current_password" value="Password Sementara" />
                <div class="relative mt-1">
                    <x-form.text-input id="current_password" class="block w-full pr-10" ::type="show ? 'text' : 'password'"
                        name="current_password" required />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none">
                        <i x-show="!show" class="ri-eye-line text-lg"></i>
                        <i x-show="show" x-cloak class="ri-eye-off-line text-lg"></i>
                    </button>
                </div>
                <x-form.input-error :messages="$errors->get('current_password')" class="mt-1" />
            </div>

            <!-- New Password -->
            <div class="mb-4" x-data="{ show: false }">
                <x-form.input-label for="password" value="Password Baru" />
                <div class="relative mt-1">
                    <x-form.text-input id="password" class="block w-full pr-10" ::type="show ? 'text' : 'password'" name="password"
                        required />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none">
                        <i x-show="!show" class="ri-eye-line text-lg"></i>
                        <i x-show="show" x-cloak class="ri-eye-off-line text-lg"></i>
                    </button>
                </div>
                <x-form.input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6" x-data="{ show: false }">
                <x-form.input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                <div class="relative mt-1">
                    <x-form.text-input id="password_confirmation" class="block w-full pr-10" ::type="show ? 'text' : 'password'"
                        name="password_confirmation" required />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none">
                        <i x-show="!show" class="ri-eye-line text-lg"></i>
                        <i x-show="show" x-cloak class="ri-eye-off-line text-lg"></i>
                    </button>
                </div>
                <x-form.input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <x-button.primary-button class="w-full justify-center bg-[#597928] hover:bg-[#597928]/90">
                Simpan Password Baru
            </x-button.primary-button>
        </form>
    </div>
</x-guest-layout>
