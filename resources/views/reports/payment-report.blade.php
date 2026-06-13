<x-app-layout>
    <div class="py-6">

        <div class="flex flex-col sm:flex-row md:items-center md:justify-between pb-6 gap-2">
            <h1 class="font-semibold text-xl">
                Payment Report
            </h1>

            <div
                class=" flex items-center gap-3 px-4 py-3 rounded-lg border border-green-200 bg-green-50 w-full sm:w-auto">
                <i class="ri-arrow-up-circle-line text-3xl text-green-500"></i>

                <div>
                    <p class="text-xs text-gray-500">
                        Total Income
                    </p>

                    <p class="font-bold text-lg text-green-600">
                        {{ rupiah($totalIncome) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-3 gap-2">
                    <form method="GET" class="flex flex-col lg:flex-row lg:flex-wrap gap-3">
                        <x-form.text-input type="text" name="search" :value="request('search')"
                            placeholder="Find a student..." />

                        <x-form.text-input type="month" name="month" :value="request('month')" placeholder="e.g. 2026-06" />

                        <x-form.select-input name="payment_method">

                            <option value="">All</option>

                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}"
                                    {{ request('payment_method') == $method->id ? 'selected' : '' }}>
                                    {{ $method->name }}
                                </option>
                            @endforeach

                        </x-form.select-input>

                        <x-button.primary-button>
                            {{ __('Filter') }}
                        </x-button.primary-button>
                    </form>

                    <x-link-button.primary-link :href="route('reports.payments.export', request()->query())" icon="ri-export-line">
                        Export Excel
                    </x-link-button.primary-link>
                </div>


                <div class="overflow-x-auto mb-3">
                    <table id="paymentReportTable" class="min-w-full min-w-[700px]">
                        <thead>

                            <tr>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Nominal</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->bill->student->name }}</td>

                                    <td>{{ $payment->paid_at }}</td>

                                    <td>{{ $payment->paymentMethod->name }}</td>

                                    <td>{{ rupiah($payment->amount_paid) }}</td>

                                    <td>
                                        <x-link-button.primary-link :href="route('payments.receipt', $payment->id)">
                                            Print Receipt
                                        </x-link-button.primary-link>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
