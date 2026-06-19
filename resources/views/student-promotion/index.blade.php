<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between pb-6 gap-2">
            <h1 class="font-semibold text-xl">
                Student Promotions
            </h1>

            <div class="flex flex-col lg:flex-row lg:items-center gap-2">
                <h1 class="text-sm">
                    Preview Scope:
                </h1>

                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 w-full sm:w-auto">
                    <i class="ri-user-3-line text-3xl text-gray-500"></i>

                    <div>
                        <p class="text-xs text-gray-500">
                            Total Students
                        </p>

                        <p class="font-bold text-lg text-gray-600">
                            {{ $summary['total_students'] }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-lg border border-yellow-200 bg-yellow-50 w-full sm:w-auto">
                    <i class="ri-award-fill text-3xl text-yellow-500"></i>

                    <div>
                        <p class="text-xs text-gray-500">
                            Will Promote
                        </p>

                        <p class="font-bold text-lg text-yellow-600">
                            {{ $summary['promote_count'] }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-lg border border-green-200 bg-green-50 w-full sm:w-auto">
                    <i class="ri-graduation-cap-line text-3xl text-green-500"></i>

                    <div>
                        <p class="text-xs text-gray-500">
                            Will Graduate
                        </p>

                        <p class="font-bold text-lg text-green-600">
                            {{ $summary['graduate_count'] }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- <x-link-button.primary-link :href="route('students.create')" icon="ri-add-line" class="">
                Add Student
            </x-link-button.primary-link> --}}
        </div>

        <div class="bg-white shadow-sm rounded-lg">
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

                    <x-form.select-input name="classroom" id="classroom-filter" data-placeholder="All">
                    </x-form.select-input>

                    <x-button.primary-button class="w-full md:w-auto">
                        Filter
                    </x-button.primary-button>
                </form>

                <div class="overflow-x-auto mb-3">
                    <table id="studentsTable" class="min-w-full min-w-[700px]">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Current Classroom</th>
                                <th>Next Classroom</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($promotions as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>

                                    <td>
                                        {{ $student->classroom->display_name }}
                                    </td>

                                    <td>
                                        @if ($student->graduated)
                                            <span
                                                class="bg-green-100 text-green-500 font-semibold py-1 px-2 text-sm rounded-md">
                                                Graduated
                                            </span>
                                        @else
                                            <span
                                                class="bg-yellow-100 text-yellow-500 font-semibold py-1 px-2 text-sm rounded-md">
                                                {{ $student->next_classroom->display_name }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

                {{ $promotions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
