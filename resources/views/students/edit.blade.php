<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Edit Student</h1>

            <x-link-button.secondary-link :href="route('students.index')">
                Back
            </x-link-button.secondary-link>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('students.update', $student->id) }}" method="POST" id="student-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="input-group">
                                <x-form.input-label for="name" :value="__('Name')" />
                                <x-form.text-input id="name" class="block mt-1 w-full" type="text"
                                    name="name" :value="$student->name" required autofocus autocomplete="name" />
                                <x-form.input-error :messages="$errors->get('name')" />
                            </div>

                            <div class="input-group">
                                <x-form.input-label for="nis" :value="__('NIS')" />
                                <x-form.text-input id="nis" class="block mt-1 w-full" type="text"
                                    name="nis" :value="$student->nis" required autofocus autocomplete="nis" />
                                <x-form.input-error :messages="$errors->get('nis')" />
                            </div>


                            @if ($schoolSetting->education_level === 'SMK' || $schoolSetting->education_level === 'SMA')
                                <div class="input-group">
                                    <x-form.input-label for="major_id" :value="__('Majors')" />
                                    <x-form.select-input name="major_id" id="major-filter" class="mt-1">
                                        <option value="">Select Major</option>

                                        @foreach ($majors as $major)
                                            <option value="{{ $major->id }}" @selected($student->classroom->major_id == $major->id)>
                                                {{ $major->name }}
                                            </option>
                                        @endforeach
                                    </x-form.select-input>
                                    <x-form.input-error :messages="$errors->get('major_id')" />
                                </div>

                                <div class="input-group">
                                    <x-form.input-label for="classroom_id" :value="__('Classroom')" />
                                    <x-form.select-input name="classroom_id" id="classroom-filter" class="mt-1"
                                        data-placeholder="Select Classroom" required>
                                    </x-form.select-input>
                                    <x-form.input-error :messages="$errors->get('classroom_id')" />
                                </div>
                            @endif

                            <div class="input-group">
                                <x-form.input-label for="parent_phone" :value="__('Parent Phone')" />
                                <x-form.text-input id="parent_phone" class="block mt-1 w-full" type="text"
                                    name="parent_phone" :value="$student->parent_phone" required autofocus
                                    autocomplete="parent_phone" />
                                <x-form.input-error :messages="$errors->get('parent_phone')" />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:justify-end gap-2 mt-6">
                            <x-button.primary-button>
                                {{ __('Update') }}
                            </x-button.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.selectedClassroom = "{{ $student->classroom_id }}";
    </script>
</x-app-layout>
