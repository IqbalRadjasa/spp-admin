<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Activity Logs</h1>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                {{-- <form class="flex flex-col md:flex-row md:flex-wrap gap-3 mb-6">
                    <x-form.text-input type="text" name="search" :value="request('search')" placeholder="Find a student..."
                        class="w-full md:w-80" />

                    <x-form.select-input name="status">
                        <option value="">All</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                            Paid
                        </option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>
                            Unpaid
                        </option>
                    </x-form.select-input>

                    <x-form.text-input type="month" name="billing_period" :value="request('billing_period')" placeholder="2026-06" />

                    <x-button.primary-button class="w-full md:w-auto">
                        Filter
                    </x-button.primary-button>
                </form> --}}

                <div class="overflow-x-auto mb-3">
                    <table id="billsTable" class="min-w-full min-w-[700px]">
                        <thead>

                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>Description</th>
                                <th>Created At</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($activityLogs as $activityLog)
                                <tr>
                                    <td>{{ $activityLog->user->name }}</td>
                                    <td>{{ $activityLog->action }}</td>
                                    <td>{{ $activityLog->entity_type }}</td>
                                    <td>{{ $activityLog->description }}</td>
                                    <td>{{ $activityLog->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $activityLogs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
