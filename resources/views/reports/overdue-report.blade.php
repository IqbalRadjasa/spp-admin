<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Overdue Report</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex mb-3">
                        <form method="GET">
                            <x-form.text-input type="text" name="search" :value="request('search')"
                                placeholder="Find a student..." />

                            <x-form.text-input type="month" name="billing_period" :value="request('billing_period')"
                                placeholder="2026-06" />

                            <x-button.primary-button class="ms-2">
                                {{ __('Filter') }}
                            </x-button.primary-button>
                        </form>
                    </div>

                    <table id="overdueReportTable" class="min-w-full">
                        <thead>

                            <tr>
                                <th>Student</th>
                                <th>Period</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bills as $bill)
                                <tr>
                                    <td>{{ $bill->student->name }}</td>

                                    <td>{{ $bill->billing_period }}</td>

                                    <td>{{ rupiah($bill->amount) }}</td>

                                    <td><span
                                            class="{{ $bill->status == 'paid' ? 'bg-green-600 py-1 px-2' : 'bg-red-600 py-1 px-2' }} text-white text-sm font-semibold rounded-md">{{ $bill->status }}</span>
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
