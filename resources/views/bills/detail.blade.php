<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Bill's Detail</h1>

            <x-link-button.secondary-link :href="route('bills.index')">
                Back
            </x-link-button.secondary-link>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Student Detail --}}
                    <div class="mb-10">
                        <h2 class="font-bold text-lg">Student Information</h2>
                        <hr class="mb-5">

                        <table class="text-sm">
                            <tbody>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">Student's Name</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">{{ $bill->student->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">NIS</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">{{ $bill->student->nis }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">Class</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">{{ $bill->student->class }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">Parent's Phone</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">{{ $bill->student->parent_phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Bill Detail --}}
                    <div class="mb-10">
                        <h2 class="font-bold text-lg">Bill Information</h2>
                        <hr class="mb-5">

                        <table class="text-sm">
                            <tbody>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">Billing Period</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">{{ $bill->billing_period }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">Amount</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">{{ rupiah($bill->amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-3 whitespace-nowrap">Status</td>
                                    <td class="pr-5 pb-3">:</td>
                                    <td class="pb-3">
                                        <span
                                            class="{{ $bill->status == 'paid' ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }} py-1 px-2 text-sm font-semibold rounded-md">
                                            {{ titleCase($bill->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-4 md:pr-10 pb-5 whitespace-nowrap">Escalation Status
                                    </td>
                                    <td class="pr-5 pb-5">:</td>
                                    <td class="pb-5">
                                        @switch($bill->escalation_status)
                                            @case('resolved')
                                                <span class="bg-green-100 text-green-500 font-semibold py-1 px-2  ">
                                                    {{ titleCase($bill->escalation_status) }}
                                                </span>
                                            @break

                                            @case('escalated')
                                                <span class="bg-red-100 text-red-500 font-semibold py-1 px-2 rounded-md">
                                                    {{ titleCase($bill->escalation_status) }}
                                                </span>
                                            @break

                                            @default
                                                <span class="bg-gray-100 text-gray-700 font-semibold py-1 px-2 rounded-md">
                                                    {{ titleCase($bill->escalation_status) }}
                                                </span>
                                        @endswitch
                                    </td>
                                </tr>
                                @if ($bill->escalation_status === 'escalated')
                                    <tr>
                                        <td colspan="3" class="font-semibold pr-4 md:pr-10 pb-2 whitespace-nowrap">
                                            Escalation Notes
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <form action="{{ route('bills.escalation-notes.store', $bill) }}"
                                                class="flex gap-2" method="POST">
                                                @csrf

                                                <x-form.text-input class="w-full" type="text" name="note"
                                                    :value="old('note')" />

                                                <x-button.primary-button>
                                                    {{ __('Save') }}
                                                </x-button.primary-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>


                    <h2 class="font-bold text-lg">Note's History</h2>
                    <hr class="mb-5">
                    <div class="mt-6 space-y-3">
                        @forelse ($bill->escalationNotes->sortByDesc('created_at') as $note)
                            <div class="border rounded-md p-3">
                                <div class="text-sm text-gray-500">
                                    {{ $note->user->name }} • {{ $note->created_at->format('d M Y H:i') }}
                                </div>
                                <div class="mt-2">
                                    {{ $note->note }}
                                </div>
                            </div>
                        @empty
                            <div class="border rounded-md p-3">
                                <div class="flex justify-center items-center">
                                    <span>
                                        No recent history
                                    </span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
