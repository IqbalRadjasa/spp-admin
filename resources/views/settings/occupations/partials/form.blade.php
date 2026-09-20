<form action="{{ route('settings.classrooms.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="input-group">
            <div class="flex items-center justify-between">
                <x-form.input-label for="name" :value="__('Nama Pekerjaan')" />

                <span id="classroom-preview" class="text-sm text-gray-400"></span>
            </div>
            <x-form.text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required />
            <x-form.input-error :messages="$errors->get('name')" />
        </div>

        <div class="input-group">
            <x-form.input-label for="is_active" :value="__('Status')" />
            <x-form.select-input id="is_active" name="is_active" class="mt-1">
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </x-form.select-input>
            <x-form.input-error :messages="$errors->get('is_active')" />
        </div>
    </div>


    {{-- Form Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <x-link-button.secondary-link :href="route('students.index')">
            {{ __('Batal') }}
        </x-link-button.secondary-link>

        <x-button.primary-button class="bg-[#597928] hover:bg-[#597928]/90">
            {{ $mode === 'create' ? 'Simpan Pekerjaan' : 'Perbarui Pekerjaan' }}
        </x-button.primary-button>
    </div>
</form>
