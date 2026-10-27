<x-app-layout>
    <div class="py-6 space-y-6">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-calendar-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Tahun Akademik</h1>
                    </div>
                </div>

                <x-button.primary-button x-on:click="$dispatch('open-academic-modal', { mode: 'create' })" class="gap-1">
                    <i class="ri-add-line"></i>
                    Tambah Tahun Akademik
                </x-button.primary-button>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto mb-3">
                    <x-table :headers="['Tahun', 'Status', 'Aksi']" :empty="$academicYears->isEmpty()">
                        @foreach ($academicYears as $academicYear)
                            <x-table.tr>
                                <x-table.td>
                                    {{ $academicYear->name }}
                                </x-table.td>

                                <x-table.td>
                                    <x-table.badge variant="{{ $academicYear->is_active === 1 ? 'olive' : 'danger' }}"
                                        class="capitalize">
                                        {{ $academicYear->is_active === 1 ? 'Aktif' : 'Tidak Aktif' }}
                                    </x-table.badge>
                                </x-table.td>

                                <x-table.td>
                                    <x-dropdown.dropdown align="left" width="48">
                                        <x-slot name="trigger">
                                            <button type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 hover:bg-stone-100 hover:text-stone-800 transition-colors">
                                                <i class="ri-more-2-fill text-lg"></i>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown.dropdown-button type="button"
                                                x-on:click="$dispatch('open-academic-modal', {
                                                    mode: 'edit',
                                                    year: '{{ $academicYear->name }}',
                                                    isActive: '{{ $academicYear->is_active }}',
                                                    updateUrl: '{{ route('settings.academic-years.update', $academicYear->id) }}'
                                                })"
                                                class="gap-1 edit-academic-year">
                                                Edit
                                            </x-dropdown.dropdown-button>

                                            <form method="POST"
                                                action="{{ route('settings.academic-years.destroy', $academicYear->id) }}"
                                                onsubmit="
                                                    event.preventDefault();

                                                   confirmAction( () => this.submit(),
                                                        {
                                                            text: 'Data ini akan dihapus permanen.',
                                                        }
                                                    );
                                                ">
                                                @csrf
                                                @method('DELETE')

                                                <x-dropdown.dropdown-button type="submit" class="text-red-600">
                                                    Hapus
                                                </x-dropdown.dropdown-button>
                                            </form>
                                        </x-slot>
                                    </x-dropdown.dropdown>
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table>
                </div>

                {{ $academicYears->links('vendor.pagination.default') }}
            </div>
        </div>

        {{-- Unified Academic Years Modal (Create & Edit) --}}
        <x-modal name="academic-year-modal" maxWidth="md">
            <form id="academic-year-form" x-data="{
                mode: 'create',
                actionUrl: '{{ route('settings.academic-years.store') }}',
                yearInput: '{{ now()->format('Y') }}',
                isActiveValue: '1',
            
                initModal(event) {
                    let detail = event.detail;
                    this.mode = detail.mode || 'create';
            
                    if (this.mode === 'edit') {
                        this.actionUrl = detail.updateUrl;
                        let fullYearStr = detail.year || '';
                        this.yearInput = fullYearStr.split('/')[0].trim();
                        this.isActiveValue = detail.isActive;
                    } else {
                        this.actionUrl = '{{ route('settings.academic-years.store') }}';
                        this.yearInput = '{{ now()->format('Y') }}';
                        this.isActiveValue = '1';
                    }
                },
            
                onSubmitForm() {
                    confirmAndSubmit('academic-year-form', {
                        title: this.mode === 'edit' ? 'Simpan Perubahan?' : 'Simpan Data?',
                        text: 'Pastikan data sudah benar.',
            
                    });
                }
            }"
                @open-academic-modal.window="initModal($event); $dispatch('open-modal', 'academic-year-modal')"
                :action="actionUrl" method="POST" @submit.prevent="onSubmitForm">
                @csrf

                {{-- Method Spoofing for Edit (PUT) --}}
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="p-6">
                    {{-- Dynamic Title --}}
                    <h2 class="text-lg font-semibold mb-4"
                        x-text="mode === 'edit' ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik'">
                    </h2>

                    <div class="flex items-start">
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1"
                                x-text="mode === 'edit' ? 'Tahun Akademik' : 'Tahun Mulai'"></label>

                            {{-- Input Field --}}
                            <x-form.text-input type="text" x-model="yearInput" name="year" class="w-full mt-1"
                                x-bind:placeholder="mode === 'edit' ? 'Contoh: 2026 / 2027' : 'Contoh: 2026'"
                                required />

                            <p class="text-xs text-gray-500 mt-1">
                                Sistem akan otomatis memformat menjadi <span class="font-semibold"
                                    x-text="yearInput ? yearInput + ' / ' + (parseInt(yearInput) + 1) : '2026 / 2027'"></span>
                            </p>

                            <span class="block min-h-[20px] text-red-500 text-sm" data-input-error="year"></span>

                            <label class="block text-sm font-medium text-gray-700 mt-3 mb-1">Status</label>
                            <x-form.select-input x-model="isActiveValue" name="is_active" id="is_active" class="mt-1"
                                required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </x-form.select-input>
                            <span class="block min-h-[20px] text-red-500 text-sm" data-input-error="is_active"></span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-100 flex justify-end gap-2">
                    <x-button.secondary-button type="button"
                        x-on:click="$dispatch('close-modal', 'academic-year-modal')">
                        Batal
                    </x-button.secondary-button>

                    <x-button.primary-button type="submit" x-text="mode === 'edit' ? 'Simpan Perubahan' : 'Simpan'">
                    </x-button.primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
