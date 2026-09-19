<x-app-layout>
    <div class="space-y-6">

        {{-- Top Action Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('students.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-stone-200/80 bg-white text-stone-600 hover:bg-[#FCECD8]/40 hover:text-stone-900 transition-colors shadow-2xs">
                    <i class="ri-arrow-left-line text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-stone-900">Student Profile</h1>
                    <p class="text-xs text-stone-500">View complete details and payment history</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('students.edit', $student->id) }}"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-stone-200/80 text-xs font-semibold text-stone-700 hover:bg-[#FCECD8]/50 hover:text-[#597928] transition-colors shadow-2xs">
                    <i class="ri-edit-line text-sm"></i>
                    Edit Student
                </a>

                <button type="button"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-[#597928] text-white text-xs font-semibold hover:bg-[#597928]/90 transition-colors shadow-2xs">
                    <i class="ri-printer-line text-sm"></i>
                    Print SPP Card
                </button>
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
                                {{ $student->initials }}
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
                            <span class="text-[10px] font-medium text-stone-500 uppercase tracking-wider block">SPP
                                Status</span>
                            <span class="text-xs font-bold text-emerald-600 mt-0.5 inline-block">Up to Date</span>
                        </div>
                        <div class="p-3 rounded-xl bg-stone-50 border border-stone-200/50 text-center">
                            <span
                                class="text-[10px] font-medium text-stone-500 uppercase tracking-wider block">Gender</span>
                            <span
                                class="text-xs font-bold text-stone-800 mt-0.5 inline-block">{{ $student->gender ?? 'Male' }}</span>
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
                                <span class="block text-[10px] text-stone-400 font-medium">Parent Phone</span>
                                <span
                                    class="font-medium text-stone-800">{{ $student->parent_phone ?? '+62 812-3456-7890' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-stone-600">
                            <div
                                class="w-7 h-7 rounded-lg bg-[#91AC67]/20 text-[#597928] flex items-center justify-center shrink-0">
                                <i class="ri-[#597928] ri-map-pin-line"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] text-stone-400 font-medium">Address</span>
                                <span
                                    class="font-medium text-stone-800 line-clamp-1">{{ $student->address ?? 'Jl. Raya Bogor No. 12, West Java' }}</span>
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
                        Personal Details
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
                        General Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Full Name</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->name }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">National Student ID
                                (NISN)</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->nisn ?? '0098234123' }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Place & Date of Birth</span>
                            <p class="font-bold text-stone-800 mt-1">Bogor, 14 May 2008</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Academic Year</span>
                            <p class="font-bold text-stone-800 mt-1">2025 / 2026</p>
                        </div>
                    </div>

                    <hr class="border-stone-100" />

                    <h3 class="text-sm font-bold text-stone-900 flex items-center gap-2 pt-2">
                        <i class="ri-parent-line text-[#597928]"></i>
                        Guardian Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Guardian Name</span>
                            <p class="font-bold text-stone-800 mt-1">{{ $student->parent_name ?? 'Ahmad Dahlan' }}</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200/50">
                            <span class="text-[10px] font-medium text-stone-400 uppercase">Guardian Occupation</span>
                            <p class="font-bold text-stone-800 mt-1">Civil Servant</p>
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
</x-app-layout>
