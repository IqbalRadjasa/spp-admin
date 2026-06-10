<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Overdue Report</h1>
            <div class="flex gap-2 items-center">
                <h1 class="font-semibold text-xl">Total Outstanding:</h1>
                <h1 class="font-bold text-xl text-white bg-red-500 py-1 px-2 rounded-md">{{ rupiah($totalOutstanding) }}
                </h1>
            </div>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-3">
                        <form method="GET">
                            <x-form.text-input type="text" name="search" :value="request('search')"
                                placeholder="Find a student..." />

                            <x-form.text-input type="month" name="month" :value="request('month')"
                                placeholder="e.g. 2026-06" />

                            <x-button.primary-button class="ms-2">
                                {{ __('Filter') }}
                            </x-button.primary-button>
                        </form>

                        <x-link-button.primary-link :href="route('reports.overdue.export', request()->query())" icon="ri-export-line">
                            Export Excel
                        </x-link-button.primary-link>
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

                                    <td>
                                        <span
                                            class="{{ $bill->status == 'paid' ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }} py-1 px-2 text-sm font-semibold rounded-md">
                                            {{ titleCase($bill->status) }}
                                        </span>
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
