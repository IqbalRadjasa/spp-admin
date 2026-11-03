<form id="editPasswordForm" method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6"
    x-data="{
        submitForm() {
            confirmAndSubmit('editPasswordForm', {
                title: 'Simpan Perubahan?',
                text: 'Pastikan kata sandi sudah sesuai.',
            });
        }
    }" @submit.prevent="submitForm">
    @csrf
    @method('PUT')


    <div class="bg-white rounded-xl border border-stone-200/80 shadow-xs overflow-hidden">

        {{-- Section Header --}}
        <div class="bg-[#FCECD8]/40 px-6 py-4 border-b border-stone-200/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-sm">
                    <i class="ri-lock-password-line"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#6E3511]">Ubah Kata Sandi</h3>
                    <p class="text-xs text-stone-500">Perbarui kata sandi lama.</p>
                </div>
            </div>
        </div>

        {{-- Form Inputs --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-5">
            <!-- Current Password -->
            <div x-data="{ showCurrentPassword: false }">
                <x-form.input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
                <div class="relative mt-1">
                    <x-form.text-input id="update_password_current_password" name="current_password"
                        x-bind:type="showCurrentPassword ? 'text' : 'password'" class="block w-full pr-10"
                        autocomplete="current-password" />
                    <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-500 hover:text-stone-700 focus:outline-none">
                        <i :class="showCurrentPassword ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-lg"></i>
                    </button>
                </div>
                <x-form.input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div x-data="{ showNewPassword: false }">
                <x-form.input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
                <div class="relative mt-1">
                    <x-form.text-input id="update_password_password" name="password"
                        x-bind:type="showNewPassword ? 'text' : 'password'" class="block w-full pr-10"
                        autocomplete="new-password" />
                    <button type="button" @click="showNewPassword = !showNewPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-500 hover:text-stone-700 focus:outline-none">
                        <i :class="showNewPassword ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-lg"></i>
                    </button>
                </div>
                {{-- Added Password Criteria Note --}}
                <p class="mt-1.5 text-xs text-stone-500 flex items-center gap-1">
                    <i class="ri-information-line text-stone-400"></i> Minimal 8 karakter.
                </p>
                <x-form.input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <!-- Confirm New Password -->
            <div x-data="{ showConfirmPassword: false }">
                <x-form.input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
                <div class="relative mt-1">
                    <x-form.text-input id="update_password_password_confirmation" name="password_confirmation"
                        x-bind:type="showConfirmPassword ? 'text' : 'password'" class="block w-full pr-10"
                        autocomplete="new-password" />
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-500 hover:text-stone-700 focus:outline-none">
                        <i :class="showConfirmPassword ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-lg"></i>
                    </button>
                </div>
                <x-form.input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Form Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <x-button.primary-button class="gap-2">
            <i class="ri-save-line"></i>
            Perbarui Kata Sandi
        </x-button.primary-button>
    </div>
</form>
