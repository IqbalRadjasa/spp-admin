<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Edit Student</h1>

            <x-link-button.secondary-link :href="route('students.index')">
                Back
            </x-link-button.secondary-link>
        </div>

        {{-- Form --}}
        @include('students.partials.form', [
            'mode' => 'edit',
            'student' => $student,
        ])
    </div>
    <script>
        window.selectedClassroom = "{{ $student->classroom_id }}";
    </script>
</x-app-layout>
