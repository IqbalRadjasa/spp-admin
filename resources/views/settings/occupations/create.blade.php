<x-app-layout>
    <div class="py-6">

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-user-add-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Tambah Pekerjaan Baru</h1>
                        <p class="text-xs text-gray-600 mt-0.5">Isi formulir di bawah untuk mendaftarkan pekerjaan ke
                            sistem SPP.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    @include('settings.occupations.partials.form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
