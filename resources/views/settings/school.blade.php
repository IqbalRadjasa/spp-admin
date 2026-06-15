<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">School Setting</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('settings.school-settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="input-group">
                                <x-form.input-label for="school_name" :value="__('School Name')" />
                                <x-form.text-input id="school_name" class="mt-1" type="text" name="school_name"
                                    :value="$setting->school_name" required autofocus />
                                <x-form.input-error :messages="$errors->get('school_name')" />
                            </div>

                            <div class="input-group">
                                <x-form.input-label for="education_level" :value="__('Education Level')" />
                                <x-form.select-input id="education_level" name="education_level" class="mt-1">
                                    <option value="SD" @selected(old('education_level', $setting->education_level) == 'SD')>
                                        SD
                                    </option>

                                    <option value="SMP" @selected(old('education_level', $setting->education_level) == 'SMP')>
                                        SMP
                                    </option>

                                    <option value="SMA" @selected(old('education_level', $setting->education_level) == 'SMA')>
                                        SMA
                                    </option>

                                    <option value="SMK" @selected(old('education_level', $setting->education_level) == 'SMK')>
                                        SMK
                                    </option>
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('education_level')" />
                            </div>

                            <div class="input-group">
                                <x-form.input-label for="logo" :value="__('School Logo')" />
                                <x-form.file-input id="logo" class="mt-1" type="file" name="logo"
                                    :value="old('logo')" />
                                <x-form.input-error :messages="$errors->get('logo')" />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:justify-end gap-2 mt-6">
                            <x-button.primary-button>
                                {{ __('Save') }}
                            </x-button.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
