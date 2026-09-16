@php

    $months = $monthlyIncome
        ->pluck('month')
        ->map(fn($month) => \Carbon\Carbon::create()->month($month)->translatedFormat('F'));

    $totals = $monthlyIncome->pluck('total');

@endphp

<x-app-layout>
    <div class="py-6 space-y-4">
        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-bold text-2xl uppercase">Dashboard</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <x-widget title="Total Students" :value="$totalStudents" icon="ri-graduation-cap-line" />
            <x-widget title="Unpaid Bills" :value="$unpaidBills" icon="ri-file-warning-line" />
            <x-widget title="Payment This Month" :value="$paymentThisMonth" icon="ri-wallet-3-line" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <x-widget title="Income This Month" :value="'Rp ' . shortNumber($incomeThisMonth)" icon="ri-cash-line" />
            <x-widget title="Escalated Bills" :value="$escalatedBills" icon="ri-alarm-warning-line" />
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl">
            <div class="p-6">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-10">
                    <div class="rounded-xl shadow-sm">
                        <h2 class="font-bold text-lg">Paid & Unpaid Bills</h2>
                        <div id="bill-status-chart"></div>
                    </div>
                    <div class="rounded-xl shadow-sm">
                        <h2 class="font-bold text-lg">Payment Method Distribution</h2>
                        <div id="payment-method-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl">
            <div class="p-6">
                <h2 class="font-bold text-lg">Monthly Income</h2>
                <div id="monthly-income-chart"></div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">

                    <h2 class="font-semibold text-lg">
                        Recent Payments
                    </h2>

                </div>

                <div class="overflow-x-auto rounded-t-lg">
                    <table class="min-w-full min-w-[1200px] text-sm">
                        <thead class="bg-[#91AC67]">
                            <tr class="border-b text-white">
                                <th class="text-left py-3 px-4">
                                    Payment Code
                                </th>
                                <th class="text-left py-3 px-4">
                                    Student
                                </th>
                                <th class="text-left py-3 px-4">
                                    Method
                                </th>
                                <th class="text-left py-3 px-4">
                                    Amount
                                </th>
                                <th class="text-left py-3 px-4">
                                    Paid At
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPayments as $payment)
                                <tr class="border-b">
                                    <td class="py-3 px-4">
                                        {{ $payment->payment_code }}
                                    </td>
                                    <td class="py-3 px-4">
                                        {{ $payment->bill->student->name }}
                                    </td>
                                    <td class="py-3 px-4">
                                        {{ $payment->paymentMethod->name }}
                                    </td>
                                    <td class="py-3 px-4">
                                        Rp
                                        {{ number_format($payment->amount_paid) }}
                                    </td>
                                    <td class="py-3 px-4">
                                        {{ $payment->paid_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 px-4">
                                        No recent payments
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
