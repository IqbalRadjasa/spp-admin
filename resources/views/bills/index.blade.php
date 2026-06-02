<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">List Bills</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex mb-3">
                        <form method="GET">
                            <x-text-input type="text" name="search" :value="request('search')"
                                placeholder="Find a student..." />

                            <x-select-input name="status">

                                <option value="">All</option>

                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>

                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>
                                    Unpaid
                                </option>

                            </x-select-input>

                            <x-text-input type="month" name="billing_period" :value="request('billing_period')"
                                placeholder="2026-06" />

                            <x-primary-button class="ms-2">
                                {{ __('Filter') }}
                            </x-primary-button>
                        </form>
                    </div>

                    <table id="billsTable" class="min-w-full">
                        <thead>

                            <tr>
                                <th>Student</th>
                                <th>Period</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bills as $bill)
                                <tr>
                                    <td>{{ $bill->student->name }}</td>

                                    <td>{{ $bill->billing_period }}</td>

                                    <td>{{ number_format($bill->amount) }}</td>

                                    <td><span
                                            class="{{ $bill->status == 'paid' ? 'bg-green-600 py-1 px-2' : 'bg-red-600 py-1 px-2' }} text-white text-sm font-semibold rounded-md">{{ $bill->status }}</span>
                                    </td>

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
                        </tbody>
                    </table>
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
