<x-app-layout>
    <div class="py-6 space-y-6">
        <div class="flex flex-col gap-4 text-center md:flex-row md:items-center md:justify-between">
            <h1 class="font-bold text-2xl uppercase">
                Kelas
            </h1>

            <x-button.primary-button
                x-on:click="$dispatch('open-classroom-modal', { isEdit: false, action: '{{ route('settings.classrooms.store') }}', level: '', major: '', class_number: '', is_active: '1' })"
                class="gap-1">
                <i class="ri-add-line"></i>
                Tambah Kelas
            </x-button.primary-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-4">
            <x-widget-summary title="Total Kelas" value="{{ $summary['total'] }}" icon="ri-presentation-fill" />
            <x-widget-summary title="Total Kelas Aktif" value="{{ $summary['active'] }}" icon="ri-check-fill" />
            <x-widget-summary title="Total Kelas Nonaktif" value="{{ $summary['inactive'] }}" icon="ri-close-fill" />
        </div>


        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                <form method="GET" action="{{ route('settings.classrooms.index') }}"
                    class="flex flex-col md:flex-row md:flex-wrap items-center gap-3 mb-6">

                    {{-- Filter: Level --}}
                    <x-form.select-input id="level" name="level" class="w-full md:w-auto">
                        <option value="">Semua Tingkat</option>
                        @foreach (classroomLevels() as $level)
                            <option value="{{ $level }}" @selected(request('level') == $level)>
                                Tingkat {{ $level }}
                            </option>
                        @endforeach
                    </x-form.select-input>

                    {{-- Filter: Major (SMA/SMK only) --}}
                    @if (in_array($schoolSetting->education_level, ['SMA', 'SMK']))
                        <x-form.select-input id="major_id" name="major_id" class="w-full md:w-auto">
                            <option value="">Semua Jurusan</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}" @selected(request('major_id') == $major->id)>
                                    {{ $major->name }} ({{ $major->code }})
                                </option>
                            @endforeach
                        </x-form.select-input>
                    @endif

                    {{-- Filter: Status --}}
                    <x-form.select-input name="is_active" class="w-full md:w-auto">
                        <option value="" @selected(request('is_active') === null || request('is_active') === '')>Semua Status</option>
                        <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                        <option value="0" @selected(request('is_active') === '0')>Nonaktif</option>
                    </x-form.select-input>

                    {{-- Filter: Sort --}}
                    <x-form.select-input name="sort" class="w-full md:w-auto">
                        <option value="" @selected(request('sort') === null || request('sort') === '')>
                            Terbaru
                        </option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>
                            Terlama
                        </option>
                        <option value="level" @selected(request('sort') === 'level')>
                            Tingkat & Nomor Kelas
                        </option>
                    </x-form.select-input>

                    {{-- Submit Button --}}
                    <x-button.primary-button type="submit" class="w-full md:w-auto bg-[#597928] hover:bg-[#6E3511]">
                        <i class="ri-filter-3-line mr-1"></i> Filter
                    </x-button.primary-button>

                    {{-- Reset Button (Only rendered when query parameters are active) --}}
                    @if (request()->hasAny(['level', 'major_id', 'is_active', 'sort']))
                        <a href="{{ route('settings.classrooms.index') }}"
                            class="w-full md:w-auto text-center px-4 py-2 text-sm font-medium text-stone-600 bg-stone-100 hover:bg-stone-200 rounded-lg transition-colors">
                            Reset
                        </a>
                    @endif
                </form>


                <x-table :headers="['Tingkat', 'Jurusan', 'Nama', 'Status', 'Aksi']" :empty="$classrooms->isEmpty()">
                    @foreach ($classrooms as $classroom)
                        <x-table.tr>

                            <x-table.td>
                                {{ $classroom->level }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $classroom->major->name }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                {{ $classroom->display_name }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.badge variant="{{ $classroom->is_active === 1 ? 'olive' : 'danger' }}">
                                    {{ $classroom->is_active === 1 ? 'Aktif' : 'Nonaktif' }}
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
                                            x-on:click="$dispatch('open-classroom-modal', {
                                                isEdit: true,
                                                action: '{{ route('settings.classrooms.update', $classroom) }}',
                                                level: '{{ $classroom->level }}',
                                                major: '{{ $classroom->major_id }}',
                                                class_number: '{{ addslashes($classroom->class_number) }}',
                                                is_active: '{{ $classroom->is_active }}'
                                            })">
                                            Edit
                                        </x-dropdown.dropdown-button>

                                        <form action="{{ route('settings.classrooms.destroy', $classroom->id) }}"
                                            method="POST"
                                            onsubmit="event.preventDefault(); confirmAction(() => this.submit(), { text: 'Kelas ini akan dihapus.', confirmButtonText: 'Hapus', confirmButtonColor: '#C0524E' });">
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

                {{ $classrooms->links('vendor.pagination.default') }}
            </div>
        </div>


        {{-- Classroom's Form Modal --}}
        <x-modal name="classroom-form-modal" maxWidth="md">
            <div x-data="{
                isEdit: {{ old('_method') === 'PUT' ? 'true' : 'false' }},
                action: @js(old('form_action', route('settings.classrooms.store'))),
                form: {
                    level: @js(old('level', '')),
                    major: @js(old('major_id', '')),
                    class_number: @js(old('class_number', '')),
                    is_active: @js((string) old('is_active', '1'))
                }
            }" {{-- Re-open modal automatically if validation fails --}}
                @if ($errors->any()) x-init="$nextTick(() => $dispatch('open-modal', 'classroom-form-modal'))" @endif
                x-on:open-classroom-modal.window="
                    isEdit = $event.detail.isEdit;
                    action = $event.detail.action;
                    form.level = $event.detail.level;
                    form.major = $event.detail.major;
                    form.class_number = $event.detail.class_number;
                    form.is_active = String($event.detail.is_active);
                    $dispatch('open-modal', 'classroom-form-modal');
                ">

                <form x-bind:action="action" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>

                    <input type="hidden" name="form_action" x-bind:value="action">

                    <div class="p-6">
                        <h2 class="text-lg font-bold uppercase mb-4" x-text="isEdit ? 'Edit Kelas' : 'Tambah Kelas'">
                        </h2>

                        <div class="space-y-4">
                            {{-- Tingkat --}}
                            <div class="input-group">
                                <x-form.input-label for="level" :value="__('Tingkat')" />
                                <x-form.select-input id="level" name="level" class="mt-1" x-model="form.level">
                                    <option value="">Pilih Tingkat</option>
                                    @foreach (classroomLevels() as $level)
                                        <option value="{{ $level }}">{{ $level }}</option>
                                    @endforeach
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('level')" />
                            </div>

                            {{-- Jurusan --}}
                            @if (in_array($schoolSetting->education_level, ['SMA', 'SMK']))
                                <div class="input-group">
                                    <x-form.input-label for="major" :value="__('Jurusan')" />
                                    <x-form.select-input id="major" name="major_id" class="mt-1"
                                        x-model="form.major">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach ($majors as $major)
                                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                                        @endforeach
                                    </x-form.select-input>
                                    <x-form.input-error :messages="$errors->get('major_id')" />
                                </div>
                            @endif

                            {{-- Nomor Kelas --}}
                            <div class="input-group">
                                <x-form.input-label for="class_number" :value="__('Nomor Kelas')" />
                                <x-form.text-input id="class_number" class="block mt-1 w-full" type="text"
                                    name="class_number" placeholder="Contoh: 1" x-model="form.class_number" required />
                                <x-form.input-error :messages="$errors->get('class_number')" />
                            </div>

                            {{-- Status --}}
                            <div class="input-group">
                                <x-form.input-label for="is_active" :value="__('Status')" />
                                <x-form.select-input id="is_active" name="is_active" class="mt-1 w-full"
                                    x-model="form.is_active">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('is_active')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-[#FAF8F5] border-t border-stone-200/80 flex justify-end gap-2">
                        <x-button.secondary-button type="button"
                            x-on:click="$dispatch('close-modal', 'classroom-form-modal')">
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
