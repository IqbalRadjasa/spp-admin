<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Add Major</h1>

            <x-link-button.secondary-link :href="url()->previous()">
                Back
            </x-link-button.secondary-link>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('settings.majors.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="input-group">
                                <div class="flex items-center justify-between">
                                    <x-form.input-label for="name" :value="__('Name')" />

                                    <span class="text-sm text-gray-400">
                                        Example: Rekayasa Perangkat Lunak
                                    </span>
                                </div>
                                <x-form.text-input id="name" class="block mt-1 w-full" type="text"
                                    name="name" :value="old('name')" required autofocus />
                                <x-form.input-error :messages="$errors->get('name')" />
                            </div>

                            <div class="input-group">
                                <div class="flex items-center justify-between">
                                    <x-form.input-label for="code" :value="__('Code')" />

                                    <span class="text-sm text-gray-400">
                                        Example: RPL
                                    </span>
                                </div>
                                <x-form.text-input id="code" class="block mt-1 w-full" type="text"
                                    name="code" :value="old('code')" required />
                                <x-form.input-error :messages="$errors->get('code')" />
                            </div>
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
