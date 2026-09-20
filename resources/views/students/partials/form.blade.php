<form action="{{ $mode === 'create' ? route('students.store') : route('students.update', $student->id) }}" method="POST"
    class="space-y-6">
    @csrf
    @if ($mode == 'edit')
        @method('PUT')
    @endif


    {{-- Section 1: Student Information --}}
    @include('students.partials.student-information')

    {{-- Section 2: Parent Information --}}
    @include('students.partials.parent-information')

    {{-- Form Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <x-link-button.secondary-link :href="route('students.index')">
            {{ __('Batal') }}
        </x-link-button.secondary-link>

        <x-button.primary-button class="bg-[#597928] hover:bg-[#597928]/90">
            {{ $mode === 'create' ? 'Simpan Siswa' : 'Perbarui Siswa' }}
        </x-button.primary-button>
    </div>
</form>
