<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">List Bills</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="billsTable" class="min-w-full">
                        <tr>
                            <th>Student</th>
                            <th>Period</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                        @foreach ($bills as $bill)
                            <tr>
                                <td>{{ $bill->student->name }}</td>

                                <td>{{ $bill->billing_period }}</td>

                                <td>{{ number_format($bill->amount) }}</td>

                                <td>{{ $bill->status }}</td>

                                <td>
                                    @if ($bill->status == 'unpaid')
                                        <x-secondary-link :href="route('payments.create', $bill->id)">
                                            Pay
                                        </x-secondary-link>
                                    @else
                                        Paid
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
