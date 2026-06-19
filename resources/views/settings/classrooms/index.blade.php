<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between pb-6">
            <h1 class="font-semibold text-xl">
                Classrooms
            </h1>

            <x-link-button.primary-link :href="route('settings.classrooms.create')" icon="ri-add-line" class="">
                Add Major
            </x-link-button.primary-link>
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
                                <th>Level</th>
                                <th>Name</th>
                                <th>Major</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($classrooms as $classroom)
                                <tr>
                                    <td>{{ $classroom->level }}</td>
                                    <td>{{ $classroom->display_name }}</td>
                                    <td>{{ $classroom->major->name }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-2">
                                            <x-link-button.secondary-link :href="route('settings.classrooms.edit', $classroom->id)">
                                                Edit
                                            </x-link-button.secondary-link>

                                            <form action="{{ route('settings.classrooms.destroy', $classroom->id) }}"
                                                method="POST"
                                                onsubmit="
                                                    event.preventDefault();

                                                   confirmAction( () => this.submit(),
                                                        {
                                                            text: 'This classroom will be permanently deleted.',
                                                            confirmButtonText: 'Delete'
                                                        }
                                                    );
                                                ">
                                                @csrf
                                                @method('DELETE')

                                                <x-button.danger-button>
                                                    {{ __('Delete') }}
                                                </x-button.danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

                {{ $classrooms->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
