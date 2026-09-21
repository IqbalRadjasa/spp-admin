<x-app-layout>
    <div class="py-6 space-y-6">
        <div class="flex flex-col gap-4 text-center md:flex-row md:items-center md:justify-between">
            <h1 class="font-bold text-2xl uppercase">
                Pekerjaan
            </h1>

            <x-button.primary-button
                x-on:click="$dispatch('open-occupation-modal', { isEdit: false, action: '{{ route('settings.occupations.store') }}', name: '', is_active: '1' })"
                class="gap-1">
                <i class="ri-add-line"></i>
                Tambah Pekerjaan
            </x-button.primary-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-4">
            <x-widget-summary title="Total Pekerjaan" value="{{ $summary['total'] }}" icon="ri-user-2-fill" />
            <x-widget-summary title="Total Pekerjaan Aktif" value="{{ $summary['active'] }}" icon="ri-check-fill" />
            <x-widget-summary title="Total Pekerjaan Nonaktif" value="{{ $summary['inactive'] }}"
                icon="ri-close-fill" />
        </div>

        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                <form class="flex flex-col md:flex-row md:flex-wrap gap-3 mb-6">
                    <x-form.select-input name="is_active">
                        <option value="" @selected(request('is_active') === null || request('is_active') === '')>Semua Status</option>
                        <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                        <option value="0" @selected(request('is_active') === '0')>Nonaktif</option>
                    </x-form.select-input>

                    <x-form.select-input name="sort">
                        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>
                            Terbaru
                        </option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                            Terlama
                        </option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                            Nama A-Z
                        </option>
                    </x-form.select-input>

                    <x-button.primary-button class="w-full md:w-auto">
                        Filter
                    </x-button.primary-button>
                </form>


                <x-table :headers="['Nama', 'Kode', 'Status', 'Aksi']" :empty="$occupations->isEmpty()">
                    @foreach ($occupations as $occupation)
                        <x-table.tr>

                            <x-table.td>
                                {{ $occupation->name }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $occupation->code }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                <x-table.badge variant="{{ $occupation->is_active === 1 ? 'olive' : 'danger' }}">
                                    {{ $occupation->is_active === 1 ? 'Aktif' : 'Nonaktif' }}
                                </x-table.badge>
                            </x-table.td>

                            <x-table.td>
                                <x-dropdown.dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 hover:bg-stone-100 hover:text-stone-800 transition-colors">
                                            <i class="ri-more-2-fill text-lg"></i>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown.dropdown-button type="button"
                                            x-on:click="$dispatch('open-occupation-modal', {
                                                isEdit: true,
                                                action: '{{ route('settings.occupations.update', $occupation) }}',
                                                name: '{{ addslashes($occupation->name) }}',
                                                is_active: '{{ $occupation->is_active }}'
                                            })">
                                            Edit
                                        </x-dropdown.dropdown-button>

                                        <form action="{{ route('settings.occupations.destroy', $occupation->id) }}"
                                            method="POST"
                                            onsubmit="event.preventDefault(); confirmAction(() => this.submit(), { text: 'Pekerjaan ini akan dihapus.', confirmButtonText: 'Hapus', confirmButtonColor: '#C0524E' });">
                                            @csrf
                                            @method('DELETE')

                                            <x-dropdown.dropdown-button type="submit" class="text-rose-700">
                                                Hapus
                                            </x-dropdown.dropdown-button>
                                        </form>
                                    </x-slot>
                                </x-dropdown.dropdown>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $occupations->links('vendor.pagination.default') }}
            </div>
        </div>

        {{-- Occupation Form Modal --}}
        <x-modal name="occupation-form-modal" maxWidth="md">
            <div x-data="{
                isEdit: false,
                action: '{{ route('settings.occupations.store') }}',
                form: { name: '', is_active: '1' }
            }"
                x-on:open-occupation-modal.window="
                    isEdit = $event.detail.isEdit;
                    action = $event.detail.action;
                    form.name = $event.detail.name;
                    form.is_active = String($event.detail.is_active);
                    $dispatch('open-modal', 'occupation-form-modal');
                ">

                <form x-bind:action="action" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>

                    <div class="p-6">
                        <h2 class="text-lg font-bold uppercase mb-4"
                            x-text="isEdit ? 'Edit Pekerjaan' : 'Tambah Pekerjaan'">
                        </h2>

                        <div class="space-y-4">
                            <div class="input-group">
                                <x-form.input-label for="name" :value="__('Nama Pekerjaan')" />
                                <x-form.text-input id="name" name="name" type="text"
                                    class="block mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                                    x-model="form.name" required />
                                <x-form.input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            <div class="input-group">
                                <x-form.input-label for="is_active" :value="__('Status')" />
                                <x-form.select-input id="is_active" name="is_active"
                                    class="mt-1 w-full focus:border-[#597928] focus:ring-[#597928]"
                                    x-model="form.is_active">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('is_active')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="px-6 py-4 bg-[#FAF8F5] border-t border-stone-200/80 flex justify-end gap-2">
                        <x-button.secondary-button type="button"
                            x-on:click="$dispatch('close-modal', 'occupation-form-modal')">
                            Batal
                        </x-button.secondary-button>

                        <x-button.primary-button type="submit" class="bg-[#597928] hover:bg-[#6E3511]">
                            <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambah'"></span>
                        </x-button.primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>
