<x-app-layout>
    <div class="py-6 space-y-6">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-book-2-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Laporan Tunggakan</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1">
            <x-widget-summary title="Total yang Belum Dilunasi" mode="danger" value="{{ rupiah($totalOutstanding) }}"
                icon="ri-arrow-down-circle-line" />
        </div>

        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-2">
                    <form method="GET" action="{{ url()->current() }}"
                        class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <x-form.text-input type="text" name="search" :value="request('search')"
                            placeholder="Cari nama siswa atau NIS..." class="md:w-80" />

                        <x-form.select-input name="academic_year_id" class="md:w-auto">
                            <option value="">Semua Tahun Akademik</option>
                            @foreach ($academicYears as $ay)
                                <option value="{{ $ay->id }}"
                                    {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                    {{ $ay->name }}
                                </option>
                            @endforeach
                        </x-form.select-input>

                        <x-form.select-input name="month" class="md:w-auto">
                            <option value="">Semua Bulan</option>
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </x-form.select-input>

                        <div class="flex items-center gap-2">
                            <x-button.primary-button type="submit" class="w-full sm:w-auto justify-center">
                                <i class="ri-filter-3-line mr-1"></i> Filter
                            </x-button.primary-button>

                            @if (request()->hasAny(['search', 'academic_year_id', 'month']))
                                <x-link-button.secondary-link :href="url()->current()">
                                    <i class="ri-refresh-line"></i> Reset
                                </x-link-button.secondary-link>
                            @endif
                        </div>
                    </form>

                    <x-link-button.primary-link :href="route('reports.overdue.export', request()->query())" icon="ri-export-line">
                        Export Excel
                    </x-link-button.primary-link>
                </div>

                <x-table :headers="['Nama Siswa', 'Periode', 'Nominal', 'Status']" :empty="$bills->isEmpty()">
                    @foreach ($bills as $bill)
                        <x-table.tr>
                            <x-table.td>
                                <x-table.avatar-cell :avatar="$bill->student->avatar" :initials="$bill->student->initials" :name="$bill->student->fullname"
                                    :subtitle="$bill->student->nis" />
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $bill->billing_period }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                {{ rupiah($bill->amount) }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.badge variant="{{ $bill->status === 'paid' ? 'olive' : 'danger' }}"
                                    class="capitalize">
                                    {{ $bill->status }}
                                </x-table.badge>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $bills->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</x-app-layout>
