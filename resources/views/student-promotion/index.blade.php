<x-app-layout>
    <div class="py-6 space-y-6">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Kenaikan Kelas Siswa</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
            {{-- Always Visible: Grand Total --}}
            <x-widget-summary title="Total Siswa Aktif" value="{{ $summary['total_students'] }}" icon="ri-group-fill" />

            <x-widget-summary title="Siswa Naik Kelas" value="{{ $summary['promote_count'] }}" icon="ri-award-fill" />

            <x-widget-summary title="Siswa Lulus" value="{{ $summary['graduate_count'] }}"
                icon="ri-graduation-cap-line" />
        </div>

        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                <form method="GET" action="{{ url()->current() }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                    <div class="w-full sm:w-72 md:w-80">
                        <x-form.text-input type="text" name="search" :value="request('search')" placeholder="Cari siswa"
                            class="w-full" />
                    </div>

                    <x-form.select-input id="level" name="level" class="md:w-40">
                        <option value="">Semua Tingkat</option>
                        @foreach (classroomLevels() as $level)
                            <option value="{{ $level }}" @selected(request('level') == $level)>
                                Tingkat {{ $level }}
                            </option>
                        @endforeach
                    </x-form.select-input>

                    @if (in_array($schoolSetting->education_level, ['SMA', 'SMK']))
                        <x-form.select-input id="major_id" name="major_id" class="md:w-auto">
                            <option value="">Semua Jurusan</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}" @selected(request('major_id') == $major->id)>
                                    {{ $major->name }} ({{ $major->code }})
                                </option>
                            @endforeach
                        </x-form.select-input>
                    @endif

                    <div class="flex items-center gap-2">
                        <x-button.primary-button type="submit" class="w-full sm:w-auto justify-center">
                            <i class="ri-filter-3-line mr-1"></i> Filter
                        </x-button.primary-button>

                        @if (request()->hasAny(['search', 'major']))
                            <x-link-button.secondary-link :href="url()->current()">
                                <i class="ri-refresh-line"></i> Reset
                            </x-link-button.secondary-link>
                        @endif
                    </div>
                </form>

                <x-table :headers="['Nama', 'NIS', 'Kelas Saat Ini', 'Kelas Selanjutnya']" :empty="$promotions->isEmpty()">
                    @foreach ($promotions as $promotion)
                        <x-table.tr>
                            <x-table.td>
                                {{ $promotion->fullname }}
                            </x-table.td>

                            <x-table.td>
                                {{ $promotion->nis }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $promotion->classroom->display_name }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                @if ($promotion->is_graduating)
                                    <x-table.badge variant="cream">
                                        Lulus
                                    </x-table.badge>
                                @else
                                    <x-table.badge variant="olive">
                                        {{ $promotion->next_classroom->display_name ?? 'N/A' }}
                                    </x-table.badge>
                                @endif
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $promotions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
