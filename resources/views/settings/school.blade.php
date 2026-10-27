<x-app-layout>
    <div class="py-6 space-y-6">

        {{-- Page Header Banner --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-school-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Profil Sekolah</h1>
                        <p class="text-sm text-stone-600 mt-0.5">Kelola informasi identitas institusi, kontak, dan logo
                            resmi sekolah.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-stone-200/80">
            <div class="p-8">
                <form id="schoolProfileForm" action="{{ route('settings.school.update') }}" method="POST"
                    enctype="multipart/form-data" x-data="{
                        schoolName: '{{ addslashes(old('school_name', $setting->school_name ?? '')) }}',
                        educationLevel: '{{ old('education_level', $setting->education_level ?? 'SD') }}',
                        phone: '{{ addslashes(old('phone', $setting->phone ?? '')) }}',
                        email: '{{ addslashes(old('email', $setting->email ?? '')) }}',
                        address: '{{ addslashes(old('address', $setting->address ?? '')) }}',
                        logoPreview: '{{ $setting->logo ? asset('storage/' . $setting->logo) : '' }}',
                        fileChosen(event) {
                            const file = event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = e => this.logoPreview = e.target.result;
                                reader.readAsDataURL(file);
                            }
                        },
                    
                        submitForm() {
                            confirmAndSubmit('schoolProfileForm', {
                                title: 'Simpan Perubahan?',
                                text: 'Pastikan profil sudah benar.',
                    
                            });
                        }
                    }" @submit.prevent="submitForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                        {{-- Left Column: Logo Preview & Upload Box (1 Span) --}}
                        <div
                            class="bg-stone-50/60 border border-stone-200/60 rounded-2xl p-6 flex flex-col items-center text-center">
                            <span
                                class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-4 self-start">Logo
                                Sekolah</span>

                            {{-- Logo Avatar / Preview Box --}}
                            <div
                                class="w-28 h-28 rounded-2xl bg-white border-2 border-dashed border-stone-300 flex items-center justify-center overflow-hidden shadow-2xs relative group mb-4">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!logoPreview">
                                    <div class="text-stone-400 flex flex-col items-center">
                                        <i class="ri-image-add-line text-3xl mb-1"></i>
                                        <span class="text-[10px]">No Logo</span>
                                    </div>
                                </template>
                            </div>

                            <div class="w-full">
                                <x-form.file-input id="logo" class="mt-1 text-xs w-full" type="file"
                                    name="logo" @change="fileChosen" />
                                <p class="text-[11px] text-stone-400 mt-1">PNG, JPG, atau JPEG (Maks. 2MB)</p>
                                <x-form.input-error :messages="$errors->get('logo')" class="mt-1 text-left" />
                            </div>
                        </div>

                        {{-- Right Column: Inputs (2 Spans) --}}
                        <div class="lg:col-span-2 space-y-6">

                            {{-- School Name & Education Level Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- School Name Input --}}
                                <div class="input-group">
                                    <x-form.input-label for="school_name" :value="__('Nama Sekolah')" />
                                    <div class="relative mt-1">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-stone-400">
                                            <i class="ri-school-line"></i>
                                        </span>
                                        <x-form.text-input id="school_name" class="block w-full pl-10 text-sm"
                                            type="text" name="school_name" x-model="schoolName" required autofocus
                                            placeholder="Contoh: SD Negeri 01 Sukamaju" />
                                    </div>
                                    <x-form.input-error :messages="$errors->get('school_name')" class="mt-1" />
                                </div>

                                {{-- Education Level Selector --}}
                                <div class="input-group">
                                    <x-form.input-label for="education_level" :value="__('Level Edukasi')" />
                                    <div class="relative mt-1">
                                        <x-form.select-input id="education_level" name="education_level"
                                            x-model="educationLevel" class="w-full text-sm">
                                            <option value="SD">SD (Sekolah Dasar)</option>
                                            <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                                            <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                                            <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                                        </x-form.select-input>
                                    </div>
                                    <x-form.input-error :messages="$errors->get('education_level')" class="mt-1" />
                                </div>
                            </div>

                            {{-- Phone & Email Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Phone Input --}}
                                <div class="input-group">
                                    <x-form.input-label for="phone" :value="__('No. Telepon')" />
                                    <div class="relative mt-1">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-stone-400">
                                            <i class="ri-phone-line"></i>
                                        </span>
                                        <x-form.text-input id="phone" class="block w-full pl-10 text-sm"
                                            type="text" name="phone" x-model="phone"
                                            placeholder="Contoh: 021-8765432" />
                                    </div>
                                    <x-form.input-error :messages="$errors->get('phone')" class="mt-1" />
                                </div>

                                {{-- Email Input --}}
                                <div class="input-group">
                                    <x-form.input-label for="email" :value="__('Email Sekolah')" />
                                    <div class="relative mt-1">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-stone-400">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <x-form.text-input id="email" class="block w-full pl-10 text-sm"
                                            type="email" name="email" x-model="email"
                                            placeholder="Contoh: info@sekolah.sch.id" />
                                    </div>
                                    <x-form.input-error :messages="$errors->get('email')" class="mt-1" />
                                </div>
                            </div>

                            {{-- Address Input --}}
                            <div class="input-group">
                                <x-form.input-label for="address" :value="__('Alamat Sekolah')" />
                                <div class="relative mt-1">
                                    <x-form.textarea id="address" name="address" rows="3" x-model="address"
                                        placeholder="Masukkan alamat lengkap sekolah..."></x-form.textarea>
                                </div>
                                <x-form.input-error :messages="$errors->get('address')" class="mt-1" />
                            </div>

                            {{-- Live Badge Preview Summary --}}
                            <div
                                class="p-4 bg-[#FCECD8]/30 border border-[#91AC67]/30 rounded-xl flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-[#597928] text-white flex items-center justify-center shrink-0">
                                    <i class="ri-shield-check-line"></i>
                                </div>
                                <div class="text-xs text-stone-600">
                                    <span class="block font-semibold text-stone-800">Pratinjau Identitas:</span>
                                    <span class="font-mono text-[#597928] font-bold"
                                        x-text="schoolName ? schoolName + ' (' + educationLevel + ')' : 'Belum diisi'"></span>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Form Actions Footer --}}
                    <div class="flex items-center justify-end gap-3 pt-6 mt-8 border-t border-stone-100">
                        <x-button.primary-button class="gap-2">
                            <i class="ri-save-line"></i>
                            {{ __('Simpan Perubahan') }}
                        </x-button.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
