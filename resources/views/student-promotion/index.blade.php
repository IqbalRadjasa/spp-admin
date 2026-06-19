<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between pb-6">
            <h1 class="font-semibold text-xl">
                Student Promotions
            </h1>

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
