<form action="{{ route('users.store') }}" method="POST" {{-- <form action="{{ $mode === 'create' ? route('students.store') : route('students.update', $user->id) }}" method="POST" --}} class="space-y-6">
    @csrf
    @if ($mode == 'edit')
        @method('PUT')
    @endif

    <div class="bg-white rounded-xl border border-stone-200/80 shadow-xs overflow-hidden" x-data="{
        role: '{{ old('role', $mode === 'edit' ? $user->role?->value : 'parent') }}',
        selectedOccupation: '{{ old('occupation_id', $mode === 'edit' && isset($user) ? $user->studentParent->occupation_id : '') }}',
        isOther: false,
        checkOccupation() {
            const selectedOption = this.$refs.occupationSelect?.options[this.$refs.occupationSelect.selectedIndex];
            this.isOther = selectedOption ? selectedOption.getAttribute('data-is-other') === 'true' : false;
        }
    }"
        x-init="checkOccupation()">

        {{-- Section Header --}}
        <div class="bg-[#FCECD8]/40 px-6 py-4 border-b border-stone-200/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-sm">
                    <i class="ri-user-fill"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#6E3511]">Informasi Pengguna</h3>
                    <p class="text-xs text-stone-500">Data pribadi pengguna.</p>
                </div>
            </div>

            {{-- Role Select --}}
            <div class="flex items-center gap-2">
                <x-form.input-label for="role" :value="__('Role')"
                    class="text-xs font-semibold text-stone-700 hidden sm:block" />
                <x-form.select-input name="role" id="role" x-model="role"
                    class="text-xs py-1.5 border-stone-300 rounded-lg">
                    @foreach ($roles as $roleItem)
                        <option value="{{ $roleItem->value }}">
                            {{ $roleItem->label() }}
                        </option>
                    @endforeach
                </x-form.select-input>
            </div>
        </div>

        {{-- Form Inputs --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            {{-- Full Name --}}
            <div class="input-group md:col-span-2">
                <x-form.input-label for="fullname" :value="__('Nama Lengkap')" class="text-xs font-semibold text-stone-700" />
                <x-form.text-input id="fullname" class="block mt-1" type="text" name="fullname" :value="old('fullname', $mode === 'edit' && isset($user) ? $user->studentParent->fullname : '')"
                    placeholder="Contoh: Budi Santoso" required />
                <x-form.input-error :messages="$errors->get('fullname')" class="mt-1" />
            </div>

            {{-- Nickname --}}
            <div class="input-group" x-show="role === 'parent'" x-cloak x-transition>
                <x-form.input-label for="nickname" :value="__('Nama Panggilan')" class="text-xs font-semibold text-stone-700" />
                <x-form.text-input id="nickname" class="block mt-1" type="text" name="nickname" :value="old('nickname', $mode === 'edit' && isset($user) ? $user->studentParent->nickname : '')"
                    placeholder="Contoh: Pak Budi atau Budi" />
                <x-form.input-error :messages="$errors->get('nickname')" class="mt-1" />
            </div>

            {{-- Phone / Whatsapp --}}
            <div class="input-group">
                <x-form.input-label for="phone" :value="__('Nomor Telepon (WhatsApp)')" class="text-xs font-semibold text-stone-700" />
                <x-form.text-input id="phone" class="block mt-1" type="text" name="phone" :value="old('phone', $mode === 'edit' && isset($user) ? $user->studentParent->phone : '')"
                    required placeholder="Contoh: 081234567890" />
                <x-form.input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            @if ($mode != 'edit')
                {{-- Email Address --}}
                <div class="input-group">
                    <x-form.input-label for="email" :value="__('Email')"
                        class="text-xs font-semibold text-stone-700" />
                    <x-form.text-input id="email" class="block mt-1" type="email" name="email"
                        :value="old('email', $mode === 'edit' && isset($user) ? $user->email : '')" placeholder="Contoh: orangtua@gmail.com" required />
                    <x-form.input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
            @endif

            {{-- Relationship (Parent Only) --}}
            <div class="input-group" x-show="role === 'parent'" x-cloak x-transition>
                <x-form.input-label for="relationship" :value="__('Hubungan dengan Siswa')"
                    class="text-xs font-semibold text-stone-700" />
                <x-form.select-input name="relationship" id="relationship" class="mt-1">
                    <option value="">Pilih Hubungan</option>
                    <option value="father" @selected(old('relationship', $mode === 'edit' && isset($user) ? $user->studentParent->relationship : '') == 'father')>Ayah (Father)</option>
                    <option value="mother" @selected(old('relationship', $mode === 'edit' && isset($user) ? $user->studentParent->relationship : '') == 'mother')>Ibu (Mother)</option>
                    <option value="guardian" @selected(old('relationship', $mode === 'edit' && isset($user) ? $user->studentParent->relationship : '') == 'guardian')>Wali (Guardian)</option>
                </x-form.select-input>
                <x-form.input-error :messages="$errors->get('relationship')" class="mt-1" />
            </div>

            {{-- Occupation Select (Parent Only) --}}
            <div class="input-group" x-show="role === 'parent'" x-cloak x-transition>
                <x-form.input-label for="occupation_id" :value="__('Pekerjaan')"
                    class="text-xs font-semibold text-stone-700" />
                <x-form.select-input name="occupation_id" id="occupation_id" x-ref="occupationSelect"
                    @change="checkOccupation()" class="mt-1">
                    <option value="">Pilih Pekerjaan</option>
                    @foreach ($occupations as $occupation)
                        <option value="{{ $occupation->id }}"
                            data-is-other="{{ strtolower($occupation->name) === 'lainnya' ? 'true' : 'false' }}"
                            @selected(old('occupation_id', $mode === 'edit' && isset($user) ? $user->studentParent->occupation_id : '') == $occupation->id)>
                            {{ $occupation->name }}
                        </option>
                    @endforeach
                </x-form.select-input>
                <x-form.input-error :messages="$errors->get('occupation_id')" class="mt-1" />
            </div>

            {{-- Custom Occupation (Parent Only AND "Lainnya" selected) --}}
            <div class="input-group" x-show="role === 'parent' && isOther" x-cloak x-transition>
                <x-form.input-label for="occupation_custom" :value="__('Nama Pekerjaan Khusus')"
                    class="text-xs font-semibold text-stone-700" />
                <x-form.text-input id="occupation_custom" class="block mt-1" type="text" name="occupation_custom"
                    :value="old('occupation_custom', $mode === 'edit' && isset($user) ? $user->studentParent->occupation_custom : '')" placeholder="Contoh: Wiraswasta / Freelancer" />
                <x-form.input-error :messages="$errors->get('occupation_custom')" class="mt-1" />
            </div>

            {{-- Address --}}
            <div class="input-group md:col-span-2 lg:col-span-3">
                <x-form.input-label for="address" :value="__('Alamat Rumah')" class="text-xs font-semibold text-stone-700" />
                <x-form.textarea class="mt-1" id="address" name="address" rows="3"
                    placeholder="Jl. Mawar No. 45, RT 02/RW 05..."
                    required>{{ old('address', $mode === 'edit' && isset($user) ? $user->studentParent->address : '') }}</x-form.textarea>
                <x-form.input-error :messages="$errors->get('address')" class="mt-1" />
            </div>

        </div>
    </div>


    {{-- Form Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <x-link-button.secondary-link :href="route('users.index')">
            {{ __('Batal') }}
        </x-link-button.secondary-link>

        <x-button.primary-button class="bg-[#597928] hover:bg-[#597928]/90">
            {{ $mode === 'create' ? 'Simpan Pengguna' : 'Perbarui Pengguna' }}
        </x-button.primary-button>
    </div>
</form>
