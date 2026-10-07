<x-app-layout>
    <div class="py-6 space-y-6">
        {{-- Top Action Header --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Profil Siswa</h1>
                    </div>
                </div>

                <x-link-button.secondary-link :href="route('students.edit', $student->id)" icon="ri-edit-line" class="">
                    Edit Siswa
                </x-link-button.secondary-link>
            </div>
        </div>

        {{-- Main Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left Sidebar Profile Card (4 Columns) --}}
            <div class="lg:col-span-4 space-y-6">
                <div
                    class="rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xs text-center relative overflow-hidden">

                    {{-- Decorative Header Banner --}}
                    <div class="absolute top-0 left-0 right-0 h-16 bg-gradient-to-r from-[#597928] to-[#91AC67]"></div>

                    {{-- Profile Avatar with Initials --}}
                    <div class="relative z-10 pt-4 flex flex-col items-center">
                        <div class="w-20 h-20 rounded-2xl bg-white p-1 shadow-md">
                            <div
                                class="w-full h-full rounded-xl bg-[#FCECD8] text-[#6E3511] flex items-center justify-center font-black text-2xl uppercase border border-stone-200/50">
                                @if ($student->avatar)
                                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->fullname }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ $student->initials }}
                                @endif
                            </div>
                        </div>

                        <h2 class="mt-3 text-lg font-bold text-stone-900">{{ $student->name }}</h2>

                        {{-- NIS Code Pill --}}
                        <div class="mt-1 flex items-center gap-2">
                            <x-table.code-pill>NIS: {{ $student->nis }}</x-table.code-pill>
                            <x-table.badge variant="olive">
                                {{ $student->classroom?->display_name ?? 'Unassigned' }}
                            </x-table.badge>
                        </div>
                    </div>

                    {{-- Key Quick Stats --}}
                    <div class="mt-6 pt-6 border-t border-stone-100 grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl bg-stone-50 border border-stone-200/50 text-center">
                            <span class="text-[10px] font-medium text-stone-500 uppercase tracking-wider block">Status
                                SPP</span>
                            <span class="text-xs font-bold text-emerald-600 mt-0.5 inline-block">Sudah diperbarui</span>
                        </div>
                        <div class="p-3 rounded-xl bg-stone-50 border border-stone-200/50 text-center">
                            <span
                                class="text-[10px] font-medium text-stone-500 uppercase tracking-wider block">Jenis</span>
                            <span
                                class="text-xs font-bold text-stone-800 mt-0.5 inline-block">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="mt-6 pt-6 border-t border-stone-100 text-left space-y-3">
                        <div class="flex items-center gap-3 text-xs text-stone-600">
                            <div
                                class="w-7 h-7 rounded-lg bg-[#FCECD8]/60 text-[#6E3511] flex items-center justify-center shrink-0">
                                <i class="ri-phone-line"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] text-stone-400 font-medium">Nomor Telefon</span>
                                <span class="font-medium text-stone-800">{{ '+' . $student->phone }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-stone-600">
                            <div
                                class="w-7 h-7 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center shrink-0">
                                <i class="ri-[#597928] ri-map-pin-line"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] text-stone-400 font-medium">Alamat Rumah</span>
                                <span
                                    class="font-medium text-stone-800 line-clamp-1">{{ $student->address ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right Content Area with Tabs (8 Columns) --}}
            <div class="lg:col-span-8 space-y-6" x-data="{ tab: 'details' }">

                {{-- Tab Buttons Navigation --}}
                <div class="flex items-center gap-2 p-1 rounded-2xl bg-stone-200/60 border border-stone-200/80 w-fit">
                    <button @click="tab = 'details'"
                        :class="tab === 'details' ? 'bg-white text-stone-900 shadow-2xs font-bold' :
                            'text-stone-600 hover:text-stone-900 font-medium'"
                        class="px-4 py-2 rounded-xl text-xs transition-all duration-150">
                        Data Pribadi
                    </button>
                    <button @click="tab = 'payments'"
                        :class="tab === 'payments' ? 'bg-white text-stone-900 shadow-2xs font-bold' :
                            'text-stone-600 hover:text-stone-900 font-medium'"
                        class="px-4 py-2 rounded-xl text-xs transition-all duration-150">
                        SPP Payment History
                    </button>
                </div>

                {{-- TAB 1: Student & Guardian Details --}}
                <div x-show="tab === 'details'"
                    class="rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xs space-y-6">

                    <h3 class="text-sm font-bold text-stone-900 flex items-center gap-2">
                        <i class="ri-user-3-line text-[#597928]"></i>
                        Informasi Umum
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Nama Lengkap</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->fullname }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Nama Panggilan</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->nickname }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">NIS</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->nis }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">NISN</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->nisn }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Tempat, Tinggal
                                Lahir</span>
                            <p class="font-bold text-stone-800 mt-1">
                                {{ $student->place_of_birth ? $student->place_of_birth . ', ' : '' }}{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Agama</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->religion }}</p>
                        </div>
                    </div>

                    <hr class="border-stone-100" />

                    <h3 class="text-sm font-bold text-stone-900 flex items-center gap-2 pt-2">
                        <i class="ri-parent-line text-[#597928]"></i>
                        Informasi Orang Tua / Wali
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Nama Lengkap</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $parent->studentParent->fullname }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Nama Panggilan</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $parent->studentParent->nickname }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Nomor Telefon</span>
                            <p class="font-bold text-stone-800 mt-1">{{ '+' . $parent->studentParent->phone }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Pekerjaan</span>
                            <p class="font-bold text-stone-800 mt-1">
                                {{ $parent->studentParent->occupation->code != 'OC-99' ? $parent->studentParent->occupation->name : $parent->studentParent->occupation_custom }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Hubungan</span>
                            <p class="font-bold text-stone-800 mt-1">{{ ucfirst($parent->pivot->relationship) }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Alamat Rumah</span>
                            <p class="font-bold text-stone-800 mt-1">
                                {{ $parent->studentParent->address }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: SPP Payments List (Using Reusable Table) --}}
                <div x-show="tab === 'payments'" class="space-y-4">
                    <x-table :headers="['Invoice', 'Month/Year', 'Amount', 'Date Paid', 'Status']" :empty="false">
                        <x-table.tr>
                            <x-table.td>
                                <x-table.code-pill>INV-2026-09</x-table.code-pill>
                            </x-table.td>
                            <x-table.td><span class="font-medium">September 2026</span></x-table.td>
                            <x-table.td><span class="font-mono text-stone-800">Rp 350.000</span></x-table.td>
                            <x-table.td><span class="text-stone-500">10 Sep 2026</span></x-table.td>
                            <x-table.td>
                                <x-table.badge variant="olive">Paid</x-table.badge>
                            </x-table.td>
                        </x-table.tr>

                        <x-table.tr>
                            <x-table.td>
                                <x-table.code-pill>INV-2026-08</x-table.code-pill>
                            </x-table.td>
                            <x-table.td><span class="font-medium">August 2026</span></x-table.td>
                            <x-table.td><span class="font-mono text-stone-800">Rp 350.000</span></x-table.td>
                            <x-table.td><span class="text-stone-500">08 Aug 2026</span></x-table.td>
                            <x-table.td>
                                <x-table.badge variant="olive">Paid</x-table.badge>
                            </x-table.td>
                        </x-table.tr>
                    </x-table>
                </div>
            </div>


        </div>

    </div>

    </div>
</x-app-layout>
