<x-app-layout>
    <div class="space-y-6 py-6">

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-user-add-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Tambah Siswa Baru</h1>
                        <p class="text-xs text-gray-600 mt-0.5">Isi formulir di bawah untuk mendaftarkan siswa ke sistem
                            SPP.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        @include('students.partials.form', [
            'mode' => 'create',
        ])
    </div>
</x-app-layout>
