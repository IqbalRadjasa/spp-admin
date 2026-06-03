<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Generate Bills</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('bills.generate') }}" method="POST">
                        @csrf

                        <div class="flex gap-4">
                            <div class="input-group w-1/3">
                                <div class="flex items-center justify-between">
                                    <x-form.input-label for="billing_period" :value="__('Billing Period')" />

                                    <span class="text-sm text-gray-400">
                                        Example: 2026-01
                                    </span>
                                </div>
                                <x-form.text-input id="billing_period" class="block mt-1 w-full" type="month"
                                    name="billing_period" :value="old('billing_period')" required autofocus
                                    autocomplete="billing_period" />
                                <x-form.input-error :messages="$errors->get('billing_period')" />
                            </div>

                            <div class="input-group w-1/3">
                                <div class="flex items-center justify-between">
                                    <x-form.input-label for="amount" :value="__('Nominal SPP')" />

                                    <span class="text-sm text-gray-400">
                                        Example: 1500000
                                    </span>
                                </div>
                                <x-form.text-input id="amount" class="block mt-1 w-full" type="number"
                                    name="amount" :value="old('amount')" required autofocus autocomplete="amount" />
                                <x-form.input-error :messages="$errors->get('amount')" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 gap-2">
                            <x-link-button.secondary-link :href="route('bills.index')">
                                Go to list bills
                            </x-link-button.secondary-link>
                            <x-button.primary-button>
                                {{ __('Generate') }}
                            </x-button.primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
