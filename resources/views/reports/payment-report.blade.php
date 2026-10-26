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
                        <h1 class="text-2xl font-bold text-[#6E3511]">Laporan Pembayaran</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1">
            <x-widget-summary title="Total Pemasukan" value="{{ rupiah($totalIncome) }}" icon="ri-arrow-up-circle-line" />
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-2">
                    <form id="payment-report-filter" method="GET" action="{{ url()->current() }}"
                        class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <x-form.text-input type="text" name="search" :value="request('search')"
                            placeholder="Cari nama siswa atau NIS..." class="md:w-auto" />

                        <x-form.select-input name="payment_method_id" class="md:w-auto">
                            <option value="">Semua Metode</option>
                            @foreach ($paymentMethods as $pm)
                                <option value="{{ $pm->id }}"
                                    {{ request('payment_method_id') == $pm->id ? 'selected' : '' }}>
                                    {{ $pm->name }}
                                </option>
                            @endforeach
                        </x-form.select-input>

                        <x-form.text-input type="date" name="date" :value="request('date')" class="md:w-auto" />

                        <x-form.select-input name="year" class="md:w-auto">
                            <option value="">Semua Tahun</option>
                            @foreach ($years as $year)
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

                        <div class="flex items-center gap-2">
                            <x-button.primary-button type="submit" class="w-full sm:w-auto justify-center">
                                <i class="ri-filter-3-line mr-1"></i> Filter
                            </x-button.primary-button>

                            @if (request()->hasAny(['search', 'payment_method_id', 'date', 'year', 'month']))
                                <x-link-button.secondary-link :href="url()->current()">
                                    <i class="ri-refresh-line"></i> Reset
                                </x-link-button.secondary-link>
                            @endif
                        </div>
                    </form>

                    <x-link-button.primary-link href="#" icon="ri-export-line"
                        onclick="handleAjaxExport(event, '{{ route('reports.payments.export') }}?' + new URLSearchParams(new FormData(document.getElementById('payment-report-filter'))).toString())">
                        Export Excel
                    </x-link-button.primary-link>
                </div>

                <x-table :headers="['Nama Siswa', 'Tanggal', 'Metode', 'Nominal', 'Aksi']" :empty="$payments->isEmpty()">
                    @foreach ($payments as $payment)
                        <x-table.tr>
                            <x-table.td>
                                <x-table.avatar-cell :avatar="$payment->bill->student->avatar" :initials="$payment->bill->student->initials" :name="$payment->bill->student->fullname"
                                    :subtitle="$payment->bill->student->nis" />
                            </x-table.td>

                            <x-table.td>
                                {{ $payment->paid_at }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $payment->paymentMethod->name }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                {{ rupiah($payment->amount_paid) }}
                            </x-table.td>

                            <x-table.td>
                                <x-link-button.primary-link :href="route('payments.download-receipt', $payment)" icon="ri-printer-line">
                                    {{ __('Download PDF Kuitansi') }}
                                </x-link-button.primary-link>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $payments->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</x-app-layout>
