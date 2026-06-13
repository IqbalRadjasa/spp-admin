<x-app-layout>
    <div class="py-6">
        <div class="flex flex-col sm:flex-row md:items-center md:justify-between pb-6 gap-2">
            <h1 class="font-semibold text-xl">
                Overdue Report
            </h1>

            <div class=" flex items-center gap-3 px-4 py-3 rounded-lg border border-red-200 bg-red-50 w-full sm:w-auto">
                <i class="ri-arrow-down-circle-line text-3xl text-red-500"></i>

                <div>
                    <p class="text-xs text-gray-500">
                        Total Outstanding
                    </p>

                    <p class="font-bold text-lg text-red-600">
                        {{ rupiah($totalOutstanding) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-3 gap-2">
                        <form method="GET" class="flex flex-col lg:flex-row lg:flex-wrap gap-3">
                            <x-form.text-input type="text" name="search" :value="request('search')"
                                placeholder="Find a student..." />

                            <x-form.text-input type="month" name="month" :value="request('month')"
                                placeholder="e.g. 2026-06" />

                            <x-button.primary-button>
                                {{ __('Filter') }}
                            </x-button.primary-button>
                        </form>

                        <x-link-button.primary-link :href="route('reports.overdue.export', request()->query())" icon="ri-export-line">
                            Export Excel
                        </x-link-button.primary-link>
                    </div>


                    <div class="overflow-x-auto mb-3">
                        <table id="overdueReportTable" class="min-w-full min-w-[700px]">
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
                    </div>
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
