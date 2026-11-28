<x-guest-layout>
    <div class="mb-6">
        <span class="inline-block text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-[#FCECD8] text-[#6E3511]">
            Pengaturan Awal
        </span>
        <h2 class="mt-2 text-xl font-bold text-gray-900">
            Lengkapi Profil Sekolah
        </h2>
        <p class="mt-1 text-xs text-gray-500 leading-relaxed">
            Isi identitas sekolah terlebih dahulu sebelum masuk ke dashboard. Jenjang pendidikan yang dipilih akan dikunci permanen setelah disimpan.
        </p>
    </div>

    <form id="schoolProfileSetupForm"
          action="{{ route('onboarding.school.store') }}"
          method="POST"
          enctype="multipart/form-data"
          x-data="{
              schoolName: @js(old('school_name', '')),
              educationLevel: @js(old('education_level', '')),
              phone: @js(old('phone', '')),
              email: @js(old('email', '')),
              address: @js(old('address', '')),
              logoPreview: '',
              fileChosen(event) {
                  const file = event.target.files[0];
                  if (!file) {
                      this.logoPreview = '';
                      return;
                  }
                  const reader = new FileReader();
                  reader.onload = e => this.logoPreview = e.target.result;
                  reader.readAsDataURL(file);
              }
          }"
          class="space-y-4">
        @csrf

        {{-- Logo Upload + Preview --}}
        <div class="flex items-center gap-4 p-3 rounded-xl bg-stone-50 border border-stone-200/70">
            <div class="w-16 h-16 rounded-xl bg-white border border-dashed border-stone-300 flex items-center justify-center overflow-hidden shrink-0">
                <template x-if="logoPreview">
                    <img :src="logoPreview" alt="Preview Logo" class="w-full h-full object-cover">
                </template>
                <template x-if="!logoPreview">
                    <i class="ri-image-add-line text-2xl text-stone-400"></i>
                </template>
            </div>
            <div class="flex-1 min-w-0">
                <x-form.input-label for="logo" :value="__('Logo Sekolah')" />
                <x-form.file-input id="logo" class="mt-1 text-xs w-full" type="file" name="logo" accept="image/png,image/jpeg" @change="fileChosen" />
                <p class="text-[11px] text-stone-400 mt-1">PNG, JPG, JPEG (Maks. 2MB)</p>
                <x-form.input-error :messages="$errors->get('logo')" class="mt-1" />
            </div>
        </div>

        {{-- Nama Sekolah --}}
        <div class="input-group">
            <x-form.input-label for="school_name" :value="__('Nama Sekolah')" />
            <x-form.text-input id="school_name" class="block w-full text-sm" type="text" name="school_name"
                x-model="schoolName" required autofocus placeholder="Contoh: SD Negeri 01 Sukamaju" />
            <x-form.input-error :messages="$errors->get('school_name')" class="mt-1" />
        </div>

        {{-- Jenjang Pendidikan --}}
        <div class="input-group">
            <x-form.input-label for="education_level" :value="__('Jenjang Pendidikan')" />
            <x-form.select-input id="education_level" name="education_level" x-model="educationLevel"
                class="mt-1 w-full text-sm" required>
                <option value="" disabled>Pilih Jenjang Pendidikan</option>
                <option value="SD">SD (Sekolah Dasar)</option>
                <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
            </x-form.select-input>
            <p class="mt-1 text-[11px] text-amber-700">
                <i class="ri-lock-line"></i> Tidak dapat diubah setelah profil sekolah disimpan.
            </p>
            <x-form.input-error :messages="$errors->get('education_level')" class="mt-1" />
        </div>

        {{-- Telepon & Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="input-group">
                <x-form.input-label for="phone" :value="__('No. Telepon')" />
                <x-form.text-input id="phone" class="block mt-1 w-full text-sm" type="text" name="phone"
                    x-model="phone" placeholder="021-8765432" />
                <x-form.input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <div class="input-group">
                <x-form.input-label for="email" :value="__('Email Sekolah')" />
                <x-form.text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email"
                    x-model="email" placeholder="info@sekolah.sch.id" />
                <x-form.input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
        </div>

        {{-- Alamat --}}
        <div class="input-group">
            <x-form.input-label for="address" :value="__('Alamat Sekolah')" />
            <x-form.textarea id="address" name="address" rows="2" x-model="address" class="mt-1 w-full text-sm"
                placeholder="Masukkan alamat lengkap sekolah..."></x-form.textarea>
            <x-form.input-error :messages="$errors->get('address')" class="mt-1" />
        </div>

        {{-- Live Preview --}}
        <div class="p-3 bg-[#FCECD8]/30 border border-[#91AC67]/30 rounded-xl text-xs">
            <span class="block font-semibold text-stone-700">Pratinjau:</span>
            <span class="font-mono text-[#597928] font-bold"
                x-text="schoolName ? (educationLevel ? schoolName + ' (' + educationLevel + ')' : schoolName) : 'Belum diisi'"></span>
        </div>

        <div class="pt-2">
            <x-button.primary-button type="submit" class="w-full justify-center gap-2">
                <i class="ri-check-line"></i>
                {{ __('Simpan & Lanjut ke Dashboard') }}
            </x-button.primary-button>
        </div>
    </form>
</x-guest-layout>