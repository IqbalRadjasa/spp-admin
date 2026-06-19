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
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto mb-3">
                    <table id="academicYearsTable" class="min-w-full min-w-[700px]">
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
                                    <td>
                                        @switch($academicYear->is_active)
                                            @case(1)
                                                <span
                                                    class="bg-green-100 text-green-500 font-semibold py-1 px-2 text-sm rounded-md">
                                                    Active
                                                </span>
                                            @break

                                            @case(0)
                                                <span
                                                    class="bg-red-100 text-red-500 font-semibold py-1 px-2 text-sm rounded-md">
                                                    Inactive
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="bg-gray-100 text-gray-700 font-semibold py-1 px-2 text-sm rounded-md">
                                                    N/a
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <x-dropdown.dropdown align="left" width="48">
                                            <x-slot name="trigger">

                                                <button class="px-4 py-2 bg-gray-200 rounded">
                                                    <i class="ri-list-unordered"></i>
                                                </button>

                                            </x-slot>

                                            <x-slot name="content">
                                                <form method="POST"
                                                    action="{{ route('settings.academic-years.activateAcademicYear', $academicYear->id) }}"
                                                    onsubmit="
                                                    event.preventDefault();

                                                   confirmAction( () => this.submit(),
                                                        {
                                                            text: 'This academic year will activated.',
                                                            confirmButtonColor: '#374151',
                                                            cancelButtonColor: '#6b7280'
                                                        }
                                                    );
                                                ">
                                                    @csrf
                                                    @method('PUT')

                                                    <x-dropdown.dropdown-button type="submit">
                                                        Activate
                                                    </x-dropdown.dropdown-button>
                                                </form>
                                                <x-dropdown.dropdown-button type="button"
                                                    x-on:click="$dispatch('open-modal', 'edit-academic-year')"
                                                    class="gap-1 edit-academic-year" data-id="{{ $academicYear->id }}">
                                                    Edit
                                                </x-dropdown.dropdown-button>


                                                <form method="POST"
                                                    action="{{ route('settings.academic-years.destroy', $academicYear->id) }}"
                                                    onsubmit="
                                                    event.preventDefault();

                                                   confirmAction( () => this.submit(),
                                                        {
                                                            text: 'This academic year will be permanently deleted.',
                                                            confirmButtonText: 'Delete'
                                                        }
                                                    );
                                                ">
                                                    @csrf
                                                    @method('DELETE')

                                                    <x-dropdown.dropdown-button type="submit" class="text-red-600">
                                                        Delete
                                                    </x-dropdown.dropdown-button>
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

        {{-- Create Academic Years Modal --}}
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

        {{-- Edit Academic Years Modal --}}
        <x-modal name="edit-academic-year" maxWidth="md">

            <form id="edit-academic-year-form">
                @csrf
                @method('PUT')

                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">
                        Edit Academic Year
                    </h2>

                    <div class="flex items-start gap-2">

                        <div class="w-full">
                            <x-form.text-input type="text" id="edit-input-from" name="from" class="w-full mt-1"
                                placeholder="2026" />

                            <span class="block min-h-[20px] text-red-500 text-sm" data-input-error="from">
                            </span>
                        </div>

                        <div class="pt-3">
                            <span class="text-gray-500">/</span>
                        </div>

                        <div class="w-full">
                            <x-form.text-input type="text" id="edit-input-to" name="to" class="w-full mt-1"
                                placeholder="2027" />

                            <span class="block min-h-[20px] text-red-500 text-sm" data-input-error="to">
                            </span>
                        </div>

                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-100 flex justify-end gap-2">
                    <x-button.secondary-button type="button" id="close-modal-edit-academic-year"
                        x-on:click="$dispatch('close-modal', 'edit-academic-year')">
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
