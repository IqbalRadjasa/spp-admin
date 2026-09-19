<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col gap-4 text-center md:flex-row md:items-center md:justify-between pb-6">
            <h1 class="font-bold text-2xl uppercase">
                Student Records
            </h1>

            <x-link-button.primary-link :href="route('students.create')" icon="ri-add-line" class="">
                Add Student
            </x-link-button.primary-link>
        </div>

        <div x-data="{ open: false }" class="mb-6">
            {{-- Grid Wrapper --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Always Visible: Grand Total --}}
                <x-widget-summary class="md:col-span-2 lg:col-span-2" title="Total Students" value="{{ $totalStudents }}"
                    icon="ri-group-fill" />

                {{-- Always Visible: First 2 Majors --}}
                @foreach ($majorSummaries->take(2) as $summary)
                    <x-widget-summary title="{{ $summary->name }}" value="{{ $summary->students_count }}" />
                @endforeach

                {{-- Collapsible Container: Remaining Majors --}}
                @if ($majorSummaries->count() > 2)
                    <template x-if="true">
                        <div x-show="open" x-collapse x-cloak class="contents">
                            @foreach ($majorSummaries->skip(2) as $summary)
                                <x-widget-summary title="{{ $summary->name }}" value="{{ $summary->students_count }}" />
                            @endforeach
                        </div>
                    </template>
                @endif

            </div>

            {{-- Toggle Button (Only shows if more than 2 majors exist) --}}
            @if ($majorSummaries->count() > 2)
                <div class="mt-3 flex justify-center">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-semibold text-[#597928] bg-[#FCECD8]/60 hover:bg-[#FCECD8] border border-[#91AC67]/30 rounded-full transition-all duration-200 shadow-2xs">
                        <span
                            x-text="open ? 'Show Less Majors' : 'Show All Majors ({{ $majorSummaries->count() - 2 }} More)'"></span>
                        <i class="ri-arrow-down-s-line text-sm transition-transform duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                </div>
            @endif
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
                                <th class="py-3.5 px-5 font-semibold">Action</th>
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
                                        <x-dropdown.dropdown align="right" width="48">
                                            <x-slot name="trigger">

                                                <button class="px-4 py-2 bg-gray-200 rounded">
                                                    <i class="ri-list-unordered"></i>
                                                </button>

                                            </x-slot>

                                            <x-slot name="content">
                                                <x-dropdown.dropdown-link
                                                    href="{{ route('students.edit', $student->id) }}">
                                                    Edit
                                                </x-dropdown.dropdown-link>

                                                <form action="{{ route('students.destroy', $student->id) }}"
                                                    method="POST"
                                                    onsubmit="
                                                event.preventDefault();
                                                confirmAction( () => this.submit(),
                                                    {
                                                        text: 'This student will be deleted.',
                                                        confirmButtonText: 'Delete',
                                                        confirmButtonColor: '#C0524E'
                                                    }
                                                );
                                            ">
                                                    @csrf
                                                    @method('DELETE')

                                                    <x-dropdown.dropdown-button type="submit" class="text-[#C0524E]">
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
                {{ $students->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
    <script>
        window.selectedClassroom = "{{ request('classroom') }}";
        window.selectedMajor = "";
    </script>
</x-app-layout>
