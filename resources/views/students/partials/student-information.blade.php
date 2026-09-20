@use('App\Enums\Religion')
@use('App\Enums\StudentStatus')

<div class="bg-white rounded-xl border border-stone-200/80 shadow-xs overflow-hidden">
    {{-- Section Header --}}
    <div class="bg-[#FCECD8]/40 px-6 py-4 border-b border-stone-200/60 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-sm">
                1
            </div>
            <div>
                <h3 class="text-base font-bold text-[#6E3511]">Informasi Siswa</h3>
                <p class="text-xs text-stone-500">Data pribadi dasar.</p>
            </div>
        </div>

        {{-- Status Badge --}}
        <div class="flex items-center gap-2">
            <x-form.input-label for="status" :value="__('Status')"
                class="text-xs font-semibold text-stone-700 hidden sm:block" />
            <x-form.select-input name="status" id="status"
                class="text-xs py-1.5 border-stone-300 rounded-lg focus:border-[#597928] focus:ring-[#597928]">
                @foreach (StudentStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $mode === 'edit' ? $student->status?->value : StudentStatus::ACTIVE->value) === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </x-form.select-input>
        </div>
    </div>

    {{-- Form Inputs --}}
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Avatar Preview & Upload (Full Row) --}}
        <div
            class="md:col-span-2 lg:col-span-3 flex items-center gap-4 p-4 bg-[#FCECD8]/20 rounded-xl border border-[#91AC67]/20">
            <div
                class="relative w-16 h-16 rounded-full overflow-hidden bg-stone-100 border-2 border-[#91AC67]/40 flex-shrink-0 flex items-center justify-center text-stone-400">
                @if ($mode === 'edit' && $student->avatar)
                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="Avatar"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>
            <div class="flex-grow">
                <x-form.input-label for="avatar" :value="__('Foto Siswa / Avatar')" />
                <input type="file" id="avatar" name="avatar" accept="image/*"
                    class="block w-full text-xs text-stone-500 mt-1 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#597928] file:text-[#FCECD8] hover:file:bg-[#6E3511] file:cursor-pointer file:transition-colors" />
                <x-form.input-error :messages="$errors->get('avatar')" class="mt-1" />
            </div>
        </div>

        {{-- Full Name --}}
        <div class="input-group md:col-span-2">
            <x-form.input-label for="fullname" :value="__('Nama Lengkap')" />
            <x-form.text-input id="fullname" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="fullname" :value="old('fullname', $mode === 'edit' ? $student->fullname : '')" required autofocus
                placeholder="Contoh: Muhammad Rizky Pratama" />
            <x-form.input-error :messages="$errors->get('fullname')" class="mt-1" />
        </div>

        {{-- Nickname --}}
        <div class="input-group">
            <x-form.input-label for="nickname" :value="__('Nama Panggilan')" />
            <x-form.text-input id="nickname" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="nickname" :value="old('nickname', $mode === 'edit' ? $student->nickname : '')" placeholder="Contoh: Rizky" />
            <x-form.input-error :messages="$errors->get('nickname')" class="mt-1" />
        </div>

        {{-- NIS --}}
        <div class="input-group">
            <x-form.input-label for="nis" :value="__('NIS')" />
            <x-form.text-input id="nis" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="nis" :value="old('nis', $mode === 'edit' ? $student->nis : '')" required placeholder="Contoh: 1029384" />
            <x-form.input-error :messages="$errors->get('nis')" class="mt-1" />
        </div>

        {{-- NISN --}}
        <div class="input-group">
            <x-form.input-label for="nisn" :value="__('NISN')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="nisn" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="nisn" :value="old('nisn', $mode === 'edit' ? $student->nisn : '')" placeholder="Contoh: 0051234567" />
            <x-form.input-error :messages="$errors->get('nisn')" class="mt-1" />
        </div>

        {{-- Gender --}}
        <div class="input-group">
            <x-form.input-label for="gender" :value="__('Jenis Kelamin')" />
            <x-form.select-input name="gender" id="gender"
                class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L" @selected(old('gender', $mode === 'edit' ? $student->gender : '') == 'L')>Laki-laki (Male)</option>
                <option value="P" @selected(old('gender', $mode === 'edit' ? $student->gender : '') == 'P')>Perempuan (Female)</option>
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('gender')" class="mt-1" />
        </div>

        {{-- Place of Birth --}}
        <div class="input-group">
            <x-form.input-label for="place_of_birth" :value="__('Tempat Lahir')" />
            <x-form.text-input id="place_of_birth" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="place_of_birth" :value="old('place_of_birth', $mode === 'edit' ? $student->place_of_birth : '')" placeholder="Contoh: Jakarta" />
            <x-form.input-error :messages="$errors->get('place_of_birth')" class="mt-1" />
        </div>

        {{-- Date of Birth --}}
        <div class="input-group">
            <x-form.input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
            <x-form.text-input id="date_of_birth" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="date" name="date_of_birth" :value="old('date_of_birth', $mode === 'edit' ? $student->date_of_birth : '')" />
            <x-form.input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
        </div>


        {{-- Religion --}}
        <div class="input-group">
            <x-form.input-label for="religion" :value="__('Agama')" />
            <x-form.select-input name="religion" id="religion"
                class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]">
                <option value="">Pilih Agama</option>
                @foreach (Religion::cases() as $religion)
                    <option value="{{ $religion->value }}" @selected(old('religion', $mode === 'edit' ? $student->religion?->value : '') === $religion->value)>
                        {{ $religion->label() }}
                    </option>
                @endforeach
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('religion')" class="mt-1" />
        </div>

        {{-- Phone --}}
        <div class="input-group">
            <x-form.input-label for="phone" :value="__('No Telepon (Whatsapp)')" />
            <x-form.text-input id="phone" class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                type="text" name="phone" :value="old('phone', $mode === 'edit' ? $student->phone : '')" placeholder="Contoh: 081234567890" />
            <x-form.input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        {{-- Enrollment Year --}}
        <div class="input-group">
            <x-form.input-label for="enrollment_year" :value="__('Tahun Pendaftaran')" />
            <x-form.text-input id="enrollment_year"
                class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]" type="number"
                name="enrollment_year" :value="old('enrollment_year', $mode === 'edit' ? $student->enrollment_year : date('Y'))" placeholder="e.g. 2024" min="2000" max="2099" />
            <x-form.input-error :messages="$errors->get('enrollment_year')" class="mt-1" />
        </div>

        {{-- Parent Selection --}}
        <div class="input-group">
            <x-form.input-label for="parent_id" :value="__('Orang Tua / Wali')" />
            <x-form.select-input name="parent_id" id="parent_id"
                class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]">
                <option value="">Pilih Orang Tua / Wali</option>
                {{-- @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $mode === 'edit' ? $student->parent_id : '') == $parent->id)>
                        {{ $parent->fullname ?? $parent->name }} ({{ $parent->phone ?? 'No Phone' }})
                    </option>
                @endforeach --}}
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('parent_id')" class="mt-1" />
        </div>

        {{-- Major & Classroom Selection --}}
        @if (in_array($schoolSetting->education_level, ['SMK', 'SMA']))
            <div class="input-group">
                <x-form.input-label for="major_id" :value="__('Jurusan')" />
                <x-form.select-input name="major_id" id="major-filter"
                    class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]">
                    <option value="">Pilih Jurusan</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->id }}" @selected(old('major_id', $mode === 'edit' ? optional($student->classroom)->major_id : '') == $major->id)>
                            {{ $major->name }}
                        </option>
                    @endforeach
                </x-form.select-input>
                <x-form.input-error :messages="$errors->get('major_id')" class="mt-1" />
            </div>
        @endif

        <div class="input-group">
            <x-form.input-label for="classroom_id" :value="__('Kelas')" />
            <x-form.select-input name="classroom_id" id="classroom-filter"
                class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]" required>
                <option value="">Pilih Kelas</option>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" @selected(old('classroom_id', $mode === 'edit' ? $student->classroom_id : '') == $classroom->id)>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('classroom_id')" class="mt-1" />
        </div>

        {{-- Address (Full Span) --}}
        <div class="input-group md:col-span-2 lg:col-span-3">
            <x-form.input-label for="address" :value="__('Alamat Rumah')" />
            <textarea id="address" name="address" rows="3"
                class="block mt-1 w-full rounded-lg border-stone-300 text-sm focus:border-[#597928] focus:ring-[#597928] transition duration-150"
                placeholder="Jl. Raya Utama No. 123, RT 01/RW 02...">{{ old('address', $mode === 'edit' ? $student->address : '') }}</textarea>
            <x-form.input-error :messages="$errors->get('address')" class="mt-1" />
        </div>

    </div>
</div>
