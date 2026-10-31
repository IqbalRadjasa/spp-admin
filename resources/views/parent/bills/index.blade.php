<x-app-layout>
    <div class="py-6 space-y-6">

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Tagihan & SPP</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ url()->current() }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                    <x-form.select-input name="year" class="md:w-auto">
                        <option value="">Semua Tahun</option>
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
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

                    <x-form.select-input name="status" class="md:w-auto">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Telah Dibayar
                        </option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas
                        </option>
                    </x-form.select-input>

                    <div class="flex items-center gap-2">
                        <x-button.primary-button type="submit" class="w-full sm:w-auto justify-center">
                            <i class="ri-filter-3-line mr-1"></i> Filter
                        </x-button.primary-button>

                        @if (request()->hasAny(['year', 'month', 'status']))
                            <x-link-button.secondary-link :href="url()->current()">
                                <i class="ri-refresh-line"></i> Reset
                            </x-link-button.secondary-link>
                        @endif
                    </div>
                </form>

                <x-table :headers="['Jenis Tagihan', 'Periode', 'Nominal', 'Status', 'Aksi']" :empty="$bills->isEmpty()">
                    @foreach ($bills as $bill)
                        <x-table.tr>
                            <x-table.td>
                                SPP / Tagihan Sekolah
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
                                    {{ $bill->status_label }}
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
                                        <x-dropdown.dropdown-link href="{{ route('parent.bills.show', $bill->id) }}">
                                            Detail
                                        </x-dropdown.dropdown-link>

                                        @if ($bill->status == 'unpaid')
                                            <x-dropdown.dropdown-link href="{{ route('payments.create', $bill->id) }}">
                                                Bayar
                                            </x-dropdown.dropdown-link>
                                        @endif
                                    </x-slot>
                                </x-dropdown.dropdown>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $bills->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</x-app-layout>
