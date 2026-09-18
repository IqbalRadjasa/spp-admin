<x-app-layout>
    <div class="space-y-6 py-6">

        <div class="flex flex-col gap-4 text-center md:flex-row md:items-center md:justify-between pb-1">
            <h1 class="font-bold text-2xl uppercase">Add Student</h1>

            {{-- <x-link-button.secondary-link :href="route('students.index')">
                Back
            </x-link-button.secondary-link> --}}
        </div>

        {{-- Form --}}
        @include('students.partials.form', [
            'mode' => 'create',
        ])
    </div>
</x-app-layout>
