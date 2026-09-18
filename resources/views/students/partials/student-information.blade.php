<div class="bg-white rounded-xl border border-stone-200/80 shadow-xs overflow-hidden">
    {{-- Section Header --}}
    <div class="bg-[#FCECD8]/40 px-6 py-4 border-b border-stone-200/60 flex items-center gap-3">
        <div
            class="w-8 h-8 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-sm">
            1
        </div>
        <div>
            <h3 class="text-base font-bold text-stone-800">Student Information</h3>
            <p class="text-xs text-stone-500">Basic personal details and academic assignment</p>
        </div>
    </div>

    {{-- Form Inputs --}}
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div class="input-group">
            <x-form.input-label for="name" :value="__('Full Name')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $mode === 'edit' ? $student->name : '')"
                required autofocus placeholder="e.g. John Doe" />
            <x-form.input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        {{-- NIS --}}
        <div class="input-group">
            <x-form.input-label for="nis" :value="__('NIS')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis', $mode === 'edit' ? $student->nis : '')"
                required placeholder="e.g. 1029384" />
            <x-form.input-error :messages="$errors->get('nis')" class="mt-1" />
        </div>

        {{-- Major & Classroom (If High School / SMK) --}}
        @if (in_array($schoolSetting->education_level, ['SMK', 'SMA']))
            <div class="input-group">
                <x-form.input-label for="major_id" :value="__('Major')" class="text-xs font-semibold text-stone-700" />
                <x-form.select-input name="major_id" id="major-filter" class="mt-1 w-full">
                    <option value="">Select Major</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->id }}" @selected(old('major_id', $mode === 'edit' ? $student->classroom->major_id : '') == $major->id)>
                            {{ $major->name }}
                        </option>
                    @endforeach
                </x-form.select-input>
                <x-form.input-error :messages="$errors->get('major_id')" class="mt-1" />
            </div>

            <div class="input-group">
                <x-form.input-label for="classroom_id" :value="__('Classroom')" class="text-xs font-semibold text-stone-700" />
                <x-form.select-input name="classroom_id" id="classroom-filter" class="mt-1 w-full"
                    data-placeholder="Select Classroom">
                </x-form.select-input>
                <x-form.input-error :messages="$errors->get('classroom_id')" class="mt-1" />
            </div>
        @endif
    </div>
</div>
