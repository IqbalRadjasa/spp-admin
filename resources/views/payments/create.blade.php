<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">SPP Payment</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm border-4 border-double border-gray-400">
                <div class="p-6">
                    <div class="flex text-md">
                        <div class="w-1/2">
                            <p>
                                Nama Siswa:
                                <br>
                                <span class="font-semibold">
                                    {{ $bill->student->name }}
                                </span>
                            </p>
                            <br>
                            <p>
                                Periode:
                                <br>
                                <span class="font-semibold">
                                    {{ $bill->billing_period }}
                                </span>
                            </p>
                        </div>
                        <div class="w-1/2">
                            <p>
                                Nominal:
                                <br>
                                <span class="font-semibold">
                                    {{ rupiah($bill->amount) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr class="my-5 border-1 border-dashed border-gray-400">

                    <form action="{{ route('payments.store', $bill) }}" method="POST">
                        @csrf

                        <div class="flex gap-4">
                            <div class="input-group w-1/2">
                                <x-form.input-label for="name" :value="__('Payment Method')" />
                                <div class="flex gap-4 mt-1">

                                    <x-form.radio id="cash" name="payment_method_id" label="Cash" value="1"
                                        :checked="old('payment_method_id') == 1" />

                                    <x-form.radio id="transfer" name="payment_method_id" label="Transfer"
                                        value="2" :checked="old('payment_method_id') == 2" />

                                </div>
                                <x-form.input-error :messages="$errors->get('payment_method_id')" />
                            </div>

                            <div class="input-group w-1/2">
                                <x-form.input-label for="paid_at" :value="__('Payment Date')" />
                                <x-form.text-input id="paid_at" class="block mt-1 w-full" type="datetime-local"
                                    name="paid_at" :value="old('paid_at')" required autofocus autocomplete="paid_at" />
                                <x-form.input-error :messages="$errors->get('paid_at')" />
                            </div>
                        </div>

                        <div class="flex gap-4 mt-3">
                            <div class="input-group w-1/2">
                                <x-form.input-label for="amount_paid" :value="__('Total Amount')" />
                                <x-form.text-input id="amount_paid" class="block mt-1 w-full" type="number"
                                    name="amount_paid" :value="$bill->amount" required autofocus autocomplete="amount_paid"
                                    readonly />
                                <x-form.input-error :messages="$errors->get('amount_paid')" />
                            </div>

                            <div class="input-group w-1/2">
                                <x-form.input-label for="notes" :value="__('Notes')" />
                                <x-form.textarea name="notes" rows="4" class="w-full">
                                    {{ old('notes') }}
                                </x-form.textarea>
                                <x-form.input-error :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 gap-2">
                            <x-link-button.secondary-link :href="url()->previous()">
                                Back
                            </x-link-button.secondary-link>
                            <x-button.primary-button>
                                {{ __('Save Payment') }}
                            </x-button.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
