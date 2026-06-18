<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Add Classroom</h1>

            <x-link-button.secondary-link :href="route('settings.classrooms.index')" class="gap-1">
                Back
            </x-link-button.secondary-link>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('settings.classrooms.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="input-group">
                                <x-form.input-label for="level" :value="__('Level')" />
                                <x-form.select-input id="level" name="level" class="mt-1">
                                    @foreach (classroomLevels() as $level)
                                        <option value="{{ $level }}">
                                            {{ $level }}
                                        </option>
                                    @endforeach
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('level')" />
                            </div>

                            <div class="input-group">
                                <div class="flex items-center justify-between">
                                    <x-form.input-label for="name" :value="__('Class Number')" />

                                    <span id="classroom-preview" class="text-sm text-gray-400"></span>
                                </div>
                                <x-form.text-input id="name" class="block mt-1 w-full" type="text"
                                    name="name" :value="old('name')" required />
                                <x-form.input-error :messages="$errors->get('name')" />
                            </div>

                            @if (in_array($schoolSetting->education_level, ['SMA', 'SMK']))
                                <div class="input-group">
                                    <x-form.input-label for="major" :value="__('Major')" />
                                    <x-form.select-input id="major" name="major_id" class="mt-1">
                                        @foreach ($majors as $major)
                                            <option value="{{ $major->id }}" data-code="{{ $major->code }}">
                                                {{ $major->name }}
                                            </option>
                                        @endforeach
                                    </x-form.select-input>
                                    <x-form.input-error :messages="$errors->get('major_id')" />
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col md:flex-row md:justify-end gap-2 mt-6">
                            <x-button.primary-button>
                                {{ __('Submit') }}
                            </x-button.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
