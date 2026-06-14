<style>
    @page {
        size: A4;
        margin: 10mm;
    }

    @media print {

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        .receipt,
        .receipt * {
            visibility: visible;
        }


        main {
            padding: 0 !important;
            margin: 0 !important;
        }

        header,
        aside,
        .no-print {
            display: none !important;
        }


        body {
            background: white !important;
        }

        .receipt {
            box-shadow: none !important;
            border: none !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col sm:flex-row md:items-center md:justify-between pb-6 gap-2 no-print">
            <h1 class="font-semibold text-xl">Receipt Payment</h1>

            <div class="flex flex-col sm:flex-row gap-2">
                <x-link-button.secondary-link :href="route('reports.payment-report')">
                    Back
                </x-link-button.secondary-link>

                <x-button.primary-button type="button" onclick="window.print()">
                    {{ __('Print') }}
                </x-button.primary-button>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="overflow-x-auto">
                <div class="receipt p-6 min-w-[850px] max-w-[850px] mx-auto">

                    {{-- Header --}}
                    <div class="bg-gray-700 text-white text-2xl font-bold text-center py-7">
                        <h1 class="">
                            School Tuition Receipt
                        </h1>
                    </div>

                    {{-- School --}}
                    <div class="bg-gray-200 text-center py-2 mb-12">
                        <h1 class="text-lg">
                            Your School Name
                        </h1>

                        {{-- Address --}}
                        <span class="text-sm">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum ducimus quibusdam tempore
                        </span>
                    </div>

                    {{-- Receipt Code --}}
                    <div class="flex flex-col justify-end text-end text-sm mb-8">
                        <span class="font-semibold">
                            Receipt Code
                        </span>

                        <span class="bg-gray-700 text-white py-2 px-2 self-end">
                            {{ $payment->payment_code }}
                        </span>
                    </div>

                    {{-- Information --}}
                    <div class="text-base/7 font-medium mb-10">
                        <p>
                            Name of Student:
                            {{ $payment->bill->student->name }}
                        </p>

                        <p>
                            NIS:
                            {{ $payment->bill->student->nis }}
                        </p>

                        <p>
                            Period:
                            {{ $payment->bill->billing_period }}
                        </p>

                        <p>
                            Payment Date:
                            {{ $payment->paid_at }}
                        </p>

                        <p>
                            Payment Method:
                            {{ $payment->paymentMethod->name }}
                        </p>

                        <p>
                            Note:
                            {{ $payment->notes ?? '-' }}
                        </p>
                    </div>

                    {{-- Table Payment Information --}}
                    <table class="text-center">
                        <thead>
                            <tr>
                                <th class="border border-gray-500 px-3 py-2">No</th>
                                <th class="border border-gray-500 px-20 py-2">Particulars</th>
                                <th class="border border-gray-500 px-16 py-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-500 px-3 py-2">1</td>
                                <td class="border border-gray-500 px-20 py-2">Tuition Fee</td>
                                <td class="border border-gray-500 px-16 py-2">{{ rupiah($payment->bill->amount) }}</td>
                            </tr>
                            <tr class="bg-gray-300 font-semibold">
                                <td class="border border-gray-500 px-20 py-2" colspan="2">Total</td>
                                <td class="border border-gray-500 px-16 py-2">{{ rupiah($payment->bill->amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
