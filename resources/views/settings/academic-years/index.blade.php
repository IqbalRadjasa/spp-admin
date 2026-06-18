<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between pb-6">
            <h1 class="font-semibold text-xl">
                Academic Years
            </h1>

            <x-button.primary-button x-on:click="$dispatch('open-modal', 'add-academic-year')" class="gap-1">
                <i class="ri-add-line"></i>
                Add Academic Year
            </x-button.primary-button>

            {{-- <x-link-button.primary-link :href="route('settings.academic-years.create')" icon="ri-add-line" class="">
                Add Academic Year
            </x-link-button.primary-link> --}}
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                {{-- <form class="flex flex-col md:flex-row md:flex-wrap gap-3 mb-6">
                    <x-form.text-input type="text" name="search" :value="request('search')" placeholder="Find a student..."
                        class="w-full md:w-80" />

                    <x-button.primary-button class="w-full md:w-auto">
                        Filter
                    </x-button.primary-button>
                </form> --}}

                <div class="overflow-x-auto mb-3">
                    <table id="classroomsTable" class="min-w-full min-w-[700px]">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($academicYears as $academicYear)
                                <tr>
                                    <td>{{ $academicYear->name }}</td>
                                    <td>{{ $academicYear->is_active }}</td>
                                    <td>
                                        <x-dropdown.dropdown align="right" width="48">
                                            <x-slot name="trigger">

                                                <button class="px-4 py-2 bg-gray-200 rounded">
                                                    <i class="ri-list-unordered"></i>
                                                </button>

                                            </x-slot>

                                            <x-slot name="content">
                                                <x-dropdown.dropdown-link
                                                    href="{{ route('settings.academic-years.edit', $academicYear->id) }}">
                                                    Edit
                                                </x-dropdown.dropdown-link>


                                                <form method="POST"
                                                    action="{{ route('settings.academic-years.destroy', $academicYear->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <x-button.danger-button>
                                                        {{ __('Delete') }}
                                                    </x-button.danger-button>
                                                </form>
                                            </x-slot>
                                        </x-dropdown.dropdown>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

                {{ $academicYears->links() }}
            </div>
        </div>

        <x-modal name="add-academic-year" maxWidth="md">

            <form id="create-academic-year-form" data-url="{{ route('settings.academic-years.store') }}">
                @csrf

                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">
                        Add Academic Year
                    </h2>

                    <div class="flex items-start gap-2">

                        <div class="w-full">
                            <x-form.text-input type="text" name="from" class="w-full mt-1" placeholder="2026" />

                            <span class="block min-h-[20px] text-red-500 text-sm" data-input-error="from">
                            </span>
                        </div>

                        <div class="pt-3">
                            <span class="text-gray-500">/</span>
                        </div>

                        <div class="w-full">
                            <x-form.text-input type="text" name="to" class="w-full mt-1" placeholder="2027" />

                            <span class="block min-h-[20px] text-red-500 text-sm" data-input-error="to">
                            </span>
                        </div>

                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-100 flex justify-end gap-2">
                    <x-button.secondary-button type="button" id="close-modal-add-academic-year"
                        x-on:click="$dispatch('close-modal', 'add-academic-year')">
                        Cancel
                    </x-button.secondary-button>

                    <x-button.primary-button type="submit">
                        Save
                    </x-button.primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
