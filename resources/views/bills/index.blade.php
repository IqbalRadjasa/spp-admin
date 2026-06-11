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
                            <x-form.text-input type="text" name="search" :value="request('search')"
                                placeholder="Find a student..." />

                            <x-form.select-input name="status">

                                <option value="">All</option>

                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>

                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>
                                    Unpaid
                                </option>

                            </x-form.select-input>

                            <x-form.text-input type="month" name="billing_period" :value="request('billing_period')"
                                placeholder="2026-06" />

                            <x-button.primary-button class="ms-2">
                                {{ __('Filter') }}
                            </x-button.primary-button>
                        </form>
                    </div>

                    <table id="billsTable" class="min-w-full">
                        <thead>

                            <tr>
                                <th>Student</th>
                                <th>Period</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Escalation Status</th>
                                <th>Action</th>
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

                                    <td>
                                        @switch($bill->escalation_status)
                                            @case('resolved')
                                                <span
                                                    class="bg-green-100 text-green-500 font-semibold py-1 px-2 text-sm rounded-md">
                                                    {{ titleCase($bill->escalation_status) }}
                                                </span>
                                            @break

                                            @case('escalated')
                                                <span
                                                    class="bg-red-100 text-red-500 font-semibold py-1 px-2 text-sm rounded-md">
                                                    {{ titleCase($bill->escalation_status) }}
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="bg-gray-100 text-gray-700 font-semibold py-1 px-2 text-sm rounded-md">
                                                    {{ titleCase($bill->escalation_status) }}
                                                </span>
                                        @endswitch
                                    </td>

                                    <td>
                                        <x-dropdown.dropdown align="right" width="48">
                                            <x-slot name="trigger">

                                                <button class="px-4 py-2 bg-gray-200 rounded">
                                                    <i class="ri-list-unordered"></i>
                                                </button>

                                            </x-slot>

                                            <x-slot name="content">
                                                <x-dropdown.dropdown-link
                                                    href="{{ route('bills.detail', $bill->id) }}">
                                                    View Detail
                                                </x-dropdown.dropdown-link>

                                                @if ($bill->status == 'unpaid')
                                                    <x-dropdown.dropdown-link
                                                        href="{{ route('payments.create', $bill->id) }}">
                                                        Pay
                                                    </x-dropdown.dropdown-link>
                                                @endif
                                            </x-slot>
                                        </x-dropdown.dropdown>
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
