<x-app-layout>
    <div class="py-6">

        {{-- Page Header --}}
        <div class="flex items-center pb-6 justify-between">
            <div>
                <h1 class="font-extrabold text-2xl text-stone-800 tracking-tight">Form Pembayaran SPP</h1>
                <p class="text-xs text-stone-500 mt-0.5">Catat transaksi pembayaran tagihan siswa dengan aman.</p>
            </div>

            <x-link-button.secondary-link :href="route('bills.index')" icon="ri-arrow-left-circle-line" class="">
                Kembali
            </x-link-button.secondary-link>
        </div>

        {{-- Main Form Container --}}
        <div class="bg-white overflow-hidden rounded-2xl shadow-xs border border-stone-200/80 relative">
            {{-- Decorative Top Accent Line --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#91AC67] to-[#597928]"></div>

            <div class="p-6 sm:p-8">

                {{-- Bill Summary Box --}}
                <div
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 p-5 rounded-xl bg-gradient-to-br from-stone-50 via-[#FCECD8]/30 to-[#91AC67]/10 border border-stone-200/70 mb-6">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Nama Siswa</span>
                        <span class="text-base font-bold text-stone-800 mt-1">{{ $bill->student->fullname }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">NIS</span>
                        <span class="text-base font-bold text-stone-800 mt-1">{{ $bill->student->nis }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Periode Tagihan</span>
                        <span class="text-base font-bold text-stone-800 mt-1">{{ $bill->billing_period }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Nominal Tagihan</span>
                        <span class="text-lg font-extrabold text-[#597928] mt-0.5">{{ rupiah($bill->amount) }}</span>
                    </div>
                </div>

                <hr class="my-6 border-t border-stone-200/60">

                {{-- Payment Form --}}
                <form id="payment-form" action="{{ route('payments.store', $bill) }}" method="POST"
                    enctype="multipart/form-data" data-redirect-url="{{ route('bills.index') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Payment Method --}}
                        <div class="input-group">
                            <x-form.input-label for="payment_method_id" :value="__('Metode Pembayaran')" />
                            <div
                                class="flex items-center gap-6 mt-2.5 p-3 bg-stone-50/60 rounded-xl border border-stone-200/60">
                                @foreach ($paymentMethods as $pm)
                                    <x-form.radio id="{{ $pm->code }}" name="payment_method_id"
                                        label="{{ $pm->name }}" value="{{ $pm->id }}" :checked="old('payment_method_id') == $pm->id" />
                                @endforeach
                            </div>
                            <x-form.input-error :messages="$errors->get('payment_method_id')" class="mt-1" />
                        </div>

                        {{-- Payment Date --}}
                        <div class="input-group">
                            <x-form.input-label for="paid_at" :value="__('Tanggal & Waktu Pembayaran')" />
                            <x-form.text-input id="paid_at" class="mt-2 w-full" type="datetime-local" name="paid_at"
                                :value="old('paid_at', now()->format('Y-m-d\TH:i'))" required autofocus autocomplete="paid_at" />
                            <x-form.input-error :messages="$errors->get('paid_at')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Cash Payment Proof (Toggled via JS, hidden by default unless Cash is selected or old input exists) --}}
                    <div id="cash-proof-container" class="mb-6 {{ old('payment_method_id') == 1 ? '' : 'hidden' }}">
                        <div class="input-group">
                            <x-form.input-label for="cash_payment_proof" :value="__('Bukti Pembayaran Tunai (Foto/Scan)')" />
                            <input type="file" id="cash_payment_proof" name="cash_payment_proof" accept="image/*"
                                class="block w-full text-xs text-stone-500 mt-1 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#597928] file:text-[#FCECD8] hover:file:bg-[#6E3511] file:cursor-pointer file:transition-colors" />
                            <p class="text-[11px] text-stone-500 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                            <x-form.input-error :messages="$errors->get('cash_payment_proof')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Total Amount --}}
                        <div class="input-group">
                            <x-form.input-label for="amount_paid" :value="__('Jumlah Dibayar (Rp)')" />
                            <x-form.text-input id="amount_paid"
                                class="block mt-2 w-full bg-stone-100/70 cursor-not-allowed font-semibold text-stone-700"
                                type="number" name="amount_paid" :value="$bill->amount" required readonly />
                            <p class="text-[11px] text-stone-500 mt-1">Nominal disesuaikan otomatis dengan tagihan.</p>
                            <x-form.input-error :messages="$errors->get('amount_paid')" class="mt-1" />
                        </div>

                        {{-- Notes --}}
                        <div class="input-group">
                            <x-form.input-label for="notes" :value="__('Catatan (Opsional)')" />
                            <x-form.textarea name="notes" rows="3" class="mt-2 w-full"
                                placeholder="Contoh: Dibayar oleh wali murid langsung via bendahara...">
                                {{ old('notes') }}
                            </x-form.textarea>
                            <x-form.input-error :messages="$errors->get('notes')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200/60 mt-6">
                        <x-link-button.secondary-link :href="route('bills.index')">
                            Batal
                        </x-link-button.secondary-link>

                        <x-button.primary-button type="submit">
                            {{ __('Simpan Pembayaran') }}
                        </x-button.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
