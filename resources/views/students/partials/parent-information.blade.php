<div class="bg-white rounded-xl border border-stone-200/80 shadow-xs overflow-hidden">
    {{-- Section Header --}}
    <div class="bg-[#FCECD8]/40 px-6 py-4 border-b border-stone-200/60 flex items-center gap-3">
        <div
            class="w-8 h-8 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-sm">
            2
        </div>
        <div>
            <h3 class="text-base font-bold text-[#6E3511]">Informasi Orang Tua / Wali</h3>
            <p class="text-xs text-stone-500">Rincian kontak untuk pemberitahuan darurat dan tagihan</p>
        </div>
    </div>

    {{-- Form Inputs --}}
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" x-data="{
        selectedOccupation: '{{ old('occupation_id', $mode === 'edit' && isset($parent) ? $parent->occupation_id : '') }}',
        isOther: false,
        checkOccupation() {
            const selectedOption = this.$refs.occupationSelect?.options[this.$refs.occupationSelect.selectedIndex];
            this.isOther = selectedOption ? selectedOption.getAttribute('data-is-other') === 'true' : false;
        }
    }" x-init="checkOccupation()">

        {{-- Full Name --}}
        <div class="input-group md:col-span-2">
            <x-form.input-label for="parent_fullname" :value="__('Nama Lengkap Orant Tua / Wali')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="parent_fullname"
                class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]" type="text"
                name="parent_fullname" :value="old('parent_fullname', $mode === 'edit' && isset($parent) ? $parent->fullname : '')" placeholder="Contoh: Budi Santoso" />
            <x-form.input-error :messages="$errors->get('parent_fullname')" class="mt-1" />
        </div>

        {{-- Nickname --}}
        <div class="input-group">
            <x-form.input-label for="parent_nickname" :value="__('Nama Panggilan Orant Tua / Wali')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="parent_nickname"
                class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]" type="text"
                name="parent_nickname" :value="old('parent_nickname', $mode === 'edit' && isset($parent) ? $parent->nickname : '')" placeholder="Contoh: Pak Budi" />
            <x-form.input-error :messages="$errors->get('parent_nickname')" class="mt-1" />
        </div>

        {{-- Relationship --}}
        <div class="input-group">
            <x-form.input-label for="relationship" :value="__('Hubungan dengan Siswa')" class="text-xs font-semibold text-stone-700" />
            <x-form.select-input name="relationship" id="relationship"
                class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]">
                <option value="">Pilih Hubungan</option>
                <option value="father" @selected(old('relationship', $mode === 'edit' && isset($parent) ? $parent->relationship : '') == 'father')>Ayah (Father)</option>
                <option value="mother" @selected(old('relationship', $mode === 'edit' && isset($parent) ? $parent->relationship : '') == 'mother')>Ibu (Mother)</option>
                <option value="guardian" @selected(old('relationship', $mode === 'edit' && isset($parent) ? $parent->relationship : '') == 'guardian')>Wali (Guardian)</option>
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('relationship')" class="mt-1" />
        </div>

        {{-- Phone / Whatsapp --}}
        <div class="input-group">
            <x-form.input-label for="parent_phone" :value="__('Nomor Telepon (WhatsApp)')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="parent_phone" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="parent_phone" :value="old('parent_phone', $mode === 'edit' && isset($parent) ? $parent->phone : '')" required placeholder="Contoh: 081234567890" />
            <x-form.input-error :messages="$errors->get('parent_phone')" class="mt-1" />
        </div>

        {{-- Email Address --}}
        <div class="input-group">
            <x-form.input-label for="parent_email" :value="__('Alamat Email')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="parent_email" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="email" name="parent_email" :value="old('parent_email', $mode === 'edit' && isset($parent) ? $parent->email : '')" placeholder="Contoh: orangtua@gmail.com" />
            <x-form.input-error :messages="$errors->get('parent_email')" class="mt-1" />
        </div>

        {{-- Occupation Select --}}
        <div class="input-group">
            <x-form.input-label for="occupation_id" :value="__('Pekerjaan')" class="text-xs font-semibold text-stone-700" />
            <x-form.select-input name="occupation_id" id="occupation_id" x-ref="occupationSelect"
                @change="checkOccupation()" class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]">
                <option value="">Pilih Pekerjaan</option>
                {{-- @foreach ($occupations as $occupation)
                    <option value="{{ $occupation->id }}"
                        data-is-other="{{ strtolower($occupation->name) === 'other' || strtolower($occupation->name) === 'lainnya' ? 'true' : 'false' }}"
                        @selected(old('occupation_id', $mode === 'edit' && isset($parent) ? $parent->occupation_id : '') == $occupation->id)>
                        {{ $occupation->name }}
                    </option>
                @endforeach --}}
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('occupation_id')" class="mt-1" />
        </div>

        {{-- Custom Occupation (Conditionally Displayed) --}}
        <div class="input-group" x-show="isOther" x-cloak x-transition>
            <x-form.input-label for="occupation_custom" :value="__('Nama Pekerjaan Khusus')"
                class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="occupation_custom"
                class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]" type="text"
                name="occupation_custom" :value="old('occupation_custom', $mode === 'edit' && isset($parent) ? $parent->occupation_custom : '')" placeholder="e.g. Wiraswasta / Freelancer" />
            <x-form.input-error :messages="$errors->get('occupation_custom')" class="mt-1" />
        </div>

        {{-- Address --}}
        <div class="input-group md:col-span-2 lg:col-span-3">
            <x-form.input-label for="parent_address" :value="__('Alamat Rumah')" class="text-xs font-semibold text-stone-700" />
            <textarea id="parent_address" name="parent_address" rows="3"
                class="block mt-1 w-full rounded-lg border-stone-300 text-sm focus:border-[#597928] focus:ring-[#597928] transition duration-150"
                placeholder="Jl. Mawar No. 45, RT 02/RW 05...">{{ old('parent_address', $mode === 'edit' && isset($parent) ? $parent->address : '') }}</textarea>
            <x-form.input-error :messages="$errors->get('parent_address')" class="mt-1" />
        </div>

    </div>
</div>
