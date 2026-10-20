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
                        <h1 class="text-2xl font-bold text-[#6E3511]">Daftar Tagihan</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ url()->current() }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
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

                    <x-form.select-input name="status" class="md:w-auto">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </x-form.select-input>

                    <x-form.select-input name="escalation_status" class="md:w-auto">
                        <option value="">Semua Eskalasi</option>
                        <option value="normal" {{ request('escalation_status') == 'normal' ? 'selected' : '' }}>Normal
                        </option>
                        <option value="escalated" {{ request('escalation_status') == 'escalated' ? 'selected' : '' }}>
                            Meningkat</option>
                        <option value="resolved" {{ request('escalation_status') == 'resolved' ? 'selected' : '' }}>
                            Diselesaikan</option>
                    </x-form.select-input>

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

                <x-table :headers="['Nama Siswa', 'Periode', 'Nominal', 'Status', 'Eskalasi Status', 'Aksi']" :empty="$bills->isEmpty()">
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

                            <x-table.td>
                                @switch($bill->escalation_status)
                                    @case('resolved')
                                        <x-table.badge variant="olive" class="capitalize">
                                            Diselesaikan
                                        </x-table.badge>
                                    @break

                                    @case('escalated')
                                        <x-table.badge variant="danger" class="capitalize">
                                            Meningkat
                                        </x-table.badge>
                                    @break

                                    @default
                                        <x-table.badge variant="gray" class="capitalize">
                                            Normal
                                        </x-table.badge>
                                @endswitch
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
                                        <x-dropdown.dropdown-link href="{{ route('bills.show', $bill->id) }}">
                                            Detail
                                        </x-dropdown.dropdown-link>

                                        @if ($bill->status == 'unpaid')
                                            <x-dropdown.dropdown-link href="{{ route('payments.create', $bill->id) }}">
                                                Pay
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
