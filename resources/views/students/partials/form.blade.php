<form id="studentForm"
    action="{{ $mode === 'create' ? route('students.store') : route('students.update', $student->id) }}" method="POST"
    enctype="multipart/form-data" class="space-y-6" x-data="{
        submitForm() {
            confirmAndSubmit('studentForm', {
                title: `{{ $mode === 'create' ? 'Simpan Data?' : 'Simpan Perubahan?' }}`,
                text: 'Pastikan data siswa sudah benar.',
    
            });
        }
    }" @submit.prevent="submitForm">
    @csrf
    @if ($mode == 'edit')
        @method('PUT')
    @endif


    {{-- Section 1: Student Information --}}
    @include('students.partials.student-information')

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

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            errorAlert({
                title: 'Gagal!',
                text: "{{ session('error') }}",
            })
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorMessages = '<ul style="text-align: left; margin-top: 10px; padding-left: 20px;">';
            @foreach ($errors->all() as $error)
                errorMessages += '<li style="font-size: 13px; color: #555;">{{ $error }}</li>';
            @endforeach
            errorMessages += '</ul>';

            errorAlert({
                title: 'Validasi Gagal!',
                html: 'Mohon periksa kembali inputan Anda:' + errorMessages,
            })
        });
    </script>
@endif
