<div class="bg-white rounded-xl border border-stone-200/80 shadow-xs overflow-hidden">
    {{-- Section Header --}}
    <div class="bg-[#FCECD8]/40 px-6 py-4 border-b border-stone-200/60 flex items-center gap-3">
        <div
            class="w-8 h-8 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-sm">
            2
        </div>
        <div>
            <h3 class="text-base font-bold text-stone-800">Parent / Guardian Information</h3>
            <p class="text-xs text-stone-500">Contact details for emergency and billing notifications
            </p>
        </div>
    </div>

    {{-- Form Inputs --}}
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Parent Name --}}
        {{-- <div class="input-group">
        <x-form.input-label for="parent_name" :value="__('Parent / Guardian Name')"
            class="text-xs font-semibold text-stone-700" />
        <x-form.text-input id="parent_name" class="block mt-1 w-full" type="text" name="parent_name"
            :value="old('parent_name')" placeholder="e.g. Robert Doe" />
        <x-form.input-error :messages="$errors->get('parent_name')" class="mt-1" />
    </div> --}}

        {{-- Parent Phone --}}
        <div class="input-group">
            <x-form.input-label for="parent_phone" :value="__('Parent Phone Number')" class="text-xs font-semibold text-stone-700" />
            <x-form.text-input id="parent_phone" class="block mt-1 w-full" type="text" name="parent_phone"
                :value="old('parent_phone', $mode === 'edit' ? $student->parent_phone : '')" required placeholder="e.g. 081234567890" />
            <x-form.input-error :messages="$errors->get('parent_phone')" class="mt-1" />
        </div>
    </div>
</div>
