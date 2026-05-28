<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Add Student</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf

                        <div class="flex gap-4">
                            <div class="input-group w-1/4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" />
                            </div>

                            <div class="input-group w-1/4">
                                <x-input-label for="nis" :value="__('NIS')" />
                                <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis"
                                    :value="old('nis')" required autofocus autocomplete="nis" />
                                <x-input-error :messages="$errors->get('nis')" />
                            </div>

                            <div class="input-group w-1/4">
                                <x-input-label for="class" :value="__('Class')" />
                                <x-text-input id="class" class="block mt-1 w-full" type="text" name="class"
                                    :value="old('class')" required autofocus autocomplete="class" />
                                <x-input-error :messages="$errors->get('class')" />
                            </div>

                            <div class="input-group w-1/4">
                                <x-input-label for="parent_phone" :value="__('Parent Phone')" />
                                <x-text-input id="parent_phone" class="block mt-1 w-full" type="text"
                                    name="parent_phone" :value="old('parent_phone')" required autofocus
                                    autocomplete="parent_phone" />
                                <x-input-error :messages="$errors->get('parent_phone')" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 gap-2">
                            <a class="btn-secondary" href="{{ url()->previous() }}">
                                Back
                            </a>
                            <button type="submit" class="btn-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
