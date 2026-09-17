<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between pb-6">
            <h1 class="font-bold text-2xl uppercase">
                Student Records
            </h1>

            <x-link-button.primary-link :href="route('students.create')" icon="ri-add-line" class="">
                Add Student
            </x-link-button.primary-link>
        </div>

        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                <form class="flex flex-col md:flex-row md:flex-wrap gap-3 mb-6">
                    <x-form.text-input type="text" name="search" :value="request('search')" placeholder="Find a student..."
                        class="w-full md:w-80" />

                    <x-form.select-input name="major" id="major-filter">
                        <option value="">All</option>

                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" @selected(request('major') == $major->id)>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </x-form.select-input>

                    <x-button.primary-button class="w-full md:w-auto">
                        Filter
                    </x-button.primary-button>
                </form>

                <div class="overflow-x-auto mb-3 rounded-lg border border-stone-200/60 shadow-sm bg-white">
                    <table id="studentsTabl" class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr
                                class="bg-[#FCECD8]/50 text-stone-700 uppercase text-xs tracking-wider border-b border-stone-200/80">
                                <th class="py-3.5 px-5 font-semibold">Name</th>
                                <th class="py-3.5 px-5 font-semibold">NIS</th>
                                <th class="py-3.5 px-5 font-semibold">Class</th>
                                <th class="py-3.5 px-5 font-semibold text-right">Action</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="divide-y divide-stone-100 text-stone-700">
                            @foreach ($students as $student)
                                <tr class="hover:bg-stone-50/80 transition-colors duration-150">
                                    <!-- Name with Avatar Circle -->
                                    <td class="py-4 px-5 whitespace-nowrap font-medium text-stone-900">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-xs uppercase">
                                                {{ $student->initials }}
                                            </div>
                                            <span>{{ $student->name }}</span>
                                        </div>
                                    </td>

                                    <!-- NIS as Monospace Code Pill -->
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span
                                            class="font-mono text-xs bg-stone-100 text-stone-600 px-2 py-1 rounded-md border border-stone-200/50">
                                            {{ $student->nis }}
                                        </span>
                                    </td>

                                    <!-- Class as Badge -->
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#91AC67]/15 text-[#597928]">
                                            {{ $student->classroom?->display_name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- Right-aligned Actions -->
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-link-button.secondary-link :href="route('students.edit', $student->id)">
                                                Edit
                                            </x-link-button.secondary-link>

                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                                onsubmit="
                                    event.preventDefault();
                                    confirmAction( () => this.submit(),
                                        {
                                            text: 'This student will be deleted.',
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
                {{ $students->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
    <script>
        window.selectedClassroom = "{{ request('classroom') }}";
        window.selectedMajor = "";
    </script>
</x-app-layout>
