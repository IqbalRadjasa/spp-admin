<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Payment Report</h1>
            <div class="flex gap-2 items-center">
                <h1 class="font-semibold text-xl">Total Income:</h1>
                <h1 class="font-bold text-xl text-white bg-green-500 py-1 px-2 rounded-md">{{ rupiah($totalIncome) }}
                </h1>
            </div>
            {{-- <div class="flex flex-col text-right">
                <h1 class="font-semibold text-xl">Total Income</h1>
                <h1 class="font-bold text-xl text-white bg-green-500 py-1 px-2 rounded-md">{{ rupiah($totalIncome) }}
                </h1>
            </div> --}}
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex mb-3">
                        <form method="GET">
                            <x-form.text-input type="text" name="search" :value="request('search')"
                                placeholder="Find a student..." />

                            <x-form.text-input type="month" name="monthly_payment" :value="request('monthly_payment')"
                                placeholder="e.g. 2026-06" />

                            <x-form.select-input name="payment_method_id">

                                <option value="">All</option>

                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach

                            </x-form.select-input>

                            <x-button.primary-button class="ms-2">
                                {{ __('Filter') }}
                            </x-button.primary-button>
                        </form>
                    </div>

                    <table id="overdueReportTable" class="min-w-full">
                        <thead>

                            <tr>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->bill->student->name }}</td>

                                    <td>{{ $payment->paid_at }}</td>

                                    <td>{{ $payment->paymentMethod->name }}</td>

                                    <td>{{ rupiah($payment->amount_paid) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
