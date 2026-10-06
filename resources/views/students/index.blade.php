<x-app-layout>
    <div class="py-6 space-y-6">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Daftar Siswa</h1>
                    </div>
                </div>

                <x-link-button.primary-link :href="route('students.create')" icon="ri-add-line" class="">
                    Tambah Siswa
                </x-link-button.primary-link>
            </div>
        </div>

        <div x-data="{ open: false }" class="mb-6">
            {{-- Grid Wrapper --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Always Visible: Grand Total --}}
                <x-widget-summary class="md:col-span-2 lg:col-span-2" title="Total Students"
                    value="{{ $totalStudents }}" icon="ri-group-fill" />

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
                <form method="GET" action="{{ url()->current() }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                    <!-- Search Input -->
                    <div class="w-full sm:w-72 md:w-80">
                        <x-form.text-input type="text" name="search" :value="request('search')"
                            placeholder="Find a student..." class="w-full" />
                    </div>

                    <!-- Major Select -->
                    <div class="w-full sm:w-48 md:w-56">
                        <x-form.select-input name="major" id="major-filter" class="w-full">
                            <option value="">All Majors</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}" @selected(request('major') == $major->id)>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </x-form.select-input>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <x-button.primary-button type="submit" class="w-full sm:w-auto justify-center">
                            Filter
                        </x-button.primary-button>

                        @if (request()->hasAny(['search', 'major']))
                            <a href="{{ url()->current() }}"
                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-md transition text-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <x-table :headers="['Name', 'NIS', 'Class', 'Action']" :empty="$students->isEmpty()">
                    @foreach ($students as $student)
                        <x-table.tr>
                            {{-- Name with Avatar --}}
                            <x-table.td>
                                <x-table.avatar-cell :initials="$student->initials" :name="$student->name" />
                            </x-table.td>

                            {{-- NIS Code Pill --}}
                            <x-table.td>
                                <x-table.code-pill>{{ $student->nis }}</x-table.code-pill>
                            </x-table.td>

                            {{-- Classroom Badge --}}
                            <x-table.td>
                                <x-table.badge variant="olive">
                                    {{ $student->classroom?->display_name ?? 'N/A' }}
                                </x-table.badge>
                            </x-table.td>

                            {{-- Action Dropdown --}}
                            <x-table.td>
                                <x-dropdown.dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 hover:bg-stone-100 hover:text-stone-800 transition-colors">
                                            <i class="ri-more-2-fill text-lg"></i>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown.dropdown-link href="{{ route('students.show', $student->id) }}">
                                            Detail
                                        </x-dropdown.dropdown-link>

                                        <x-dropdown.dropdown-link href="{{ route('students.edit', $student->id) }}">
                                            Edit
                                        </x-dropdown.dropdown-link>

                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                            onsubmit="event.preventDefault(); confirmAction(() => this.submit(), { text: 'This student will be deleted.', confirmButtonText: 'Delete', confirmButtonColor: '#C0524E' });">
                                            @csrf
                                            @method('DELETE')
                                            <x-dropdown.dropdown-button type="submit" class="text-[#C0524E]">
                                                Delete
                                            </x-dropdown.dropdown-button>
                                        </form>
                                    </x-slot>
                                </x-dropdown.dropdown>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>
                {{ $students->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
    <script>
        window.selectedClassroom = "{{ request('classroom') }}";
        window.selectedMajor = "";
    </script>
</x-app-layout>
