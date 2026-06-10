@php

    $months = $monthlyIncome
        ->pluck('month')
        ->map(fn($month) => \Carbon\Carbon::create()->month($month)->translatedFormat('F'));

    $totals = $monthlyIncome->pluck('total');

@endphp

<x-app-layout>
    <div class="py-6">
        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Dashboard</h1>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex gap-2 mb-5">
                    <div class="w-1/3">
                        <x-widget title="Total Students" :value="$totalStudents" icon="ri-graduation-cap-line" />
                    </div>
                    <div class="w-1/3">
                        <x-widget title="Unpaid Bills" :value="$unpaidBills" icon="ri-file-warning-line" />
                    </div>
                    <div class="w-1/3">
                        <x-widget title="Payment This Month" :value="$paymentThisMonth" icon="ri-wallet-3-line" />
                    </div>
                </div>
                <div class="flex gap-2 mb-10">
                    <div class="w-1/2">
                        <x-widget title="Income This Month" :value="'Rp ' . shortNumber($incomeThisMonth)" icon="ri-cash-line" />
                    </div>
                    <div class="w-1/2">
                        <x-widget title="Escalated Bills" :value="$escalatedBills" icon="ri-alarm-warning-line" />
                    </div>
                </div>

                <div class="flex mb-10">
                    <div class="w-1/2">
                        <div class="rounded-xl shadow-sm">
                            <h2 class="font-bold text-lg">Paid & Unpaid Bills</h2>
                            <div id="bill-status-chart"></div>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="rounded-xl shadow-sm">
                            <h2 class="font-bold text-lg">Payment Method Distribution</h2>
                            <div id="payment-method-chart"></div>
                        </div>
                    </div>
                </div>

                <h2 class="font-bold text-lg">Monthly Income</h2>
                <div id="monthly-income-chart"></div>

                <div class="bg-white rounded-xl shadow-sm mt-6">

                    <div class="flex items-center justify-between mb-4">

                        <h2 class="font-semibold text-lg">
                            Recent Payments
                        </h2>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3">
                                        Payment Code
                                    </th>
                                    <th class="text-left py-3">
                                        Student
                                    </th>
                                    <th class="text-left py-3">
                                        Method
                                    </th>
                                    <th class="text-left py-3">
                                        Amount
                                    </th>
                                    <th class="text-left py-3">
                                        Paid At
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentPayments as $payment)
                                    <tr class="border-b">
                                        <td class="py-3">
                                            {{ $payment->payment_code }}
                                        </td>
                                        <td class="py-3">
                                            {{ $payment->bill->student->name }}
                                        </td>
                                        <td class="py-3">
                                            {{ $payment->paymentMethod->name }}
                                        </td>
                                        <td class="py-3">
                                            Rp
                                            {{ number_format($payment->amount_paid) }}
                                        </td>
                                        <td class="py-3">
                                            {{ $payment->paid_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            No recent payments
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.dashboardChartData = {

            months: @json($months),

            totals: @json($totals)

        };

        window.billStatusChartData = {

            paid: @json($paidBills),

            unpaid: @json($unpaidBills)

        };

        window.paymentMethodChartData = {

            labels: @json($paymentMethodLabels),

            totals: @json($paymentMethodTotals)

        };
    </script>
</x-app-layout>
