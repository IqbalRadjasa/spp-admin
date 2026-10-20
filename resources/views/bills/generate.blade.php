<x-app-layout>
    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-stone-200/80">

            {{-- Header Section with Active Academic Year Banner --}}
            <div
                class="bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white px-8 py-6 border-b border-stone-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-stone-800 flex items-center gap-2">
                        <i class="ri-file-list-3-line text-[#597928]"></i>
                        Generate Tagihan SPP Bulanan
                    </h1>
                    <p class="text-sm text-stone-500 mt-1">
                        Buat tagihan massal untuk seluruh siswa aktif berdasarkan periode bulan dan nominal SPP.
                    </p>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 bg-white border border-[#91AC67]/40 rounded-xl shadow-2xs">
                    <i class="ri-calendar-check-line text-[#597928] text-lg"></i>
                    <div class="text-xs">
                        <span class="block text-stone-400 font-medium">Tahun Akademik Aktif</span>
                        <span
                            class="font-bold text-stone-800">{{ $activeAcademicYear ? $activeAcademicYear->name : 'Belum Diatur' }}</span>
                    </div>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="p-8">
                <form id="generateBillsForm" action="{{ route('bills.generate') }}" method="POST"
                    x-data="{
                        selectedMonth: '07',
                        selectedYear: '{{ $startYear }}',
                        get finalPeriod() {
                            return this.selectedYear + '-' + this.selectedMonth;
                        },
                        submitForm() {
                            confirmAndSubmit('generateBillsForm', {
                                title: 'Konfirmasi Tindakan?',
                                text: 'Pastikan periode dan jumlah tagihan sudah benar.',
                                confirmButtonText: 'Buat',
                                confirmButtonColor: '#597928',
                            });
                        }
                    }" @submit.prevent="submitForm">
                    @csrf

                    {{-- Hidden input to submit the combined YYYY-MM format to backend --}}
                    <input type="hidden" name="billing_period" x-model="finalPeriod">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                        {{-- Billing Period Builder (Month Dropdown + Active Year) --}}
                        <div class="input-group">
                            <x-form.input-label for="month_selector" :value="__('Periode Bulan Tagihan')" />
                            <span class="text-xs text-stone-400 block mb-1">Pilih bulan ajaran untuk tagihan
                                ini</span>

                            <div class="grid grid-cols-2 gap-2 mt-1">
                                {{-- Month Select --}}
                                <x-form.select-input x-model="selectedMonth" id="month_selector" name="month">
                                    <option value="">Semua Bulan</option>
                                    @foreach ($months as $num => $name)
                                        <option value="{{ $num }}"
                                            {{ request('month') == $num ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('month')" class="mt-1" />


                                {{-- Year Select derived from active academic year --}}
                                <x-form.select-input x-model="selectedYear" name="year" required>
                                    @if ($activeAcademicYear)
                                        <option value="{{ $year1 }}">{{ $year1 }}</option>
                                        <option value="{{ $year2 }}">{{ $year2 }}</option>
                                    @else
                                        <option value="{{ now()->format('Y') }}">{{ now()->format('Y') }}</option>
                                    @endif
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('year')" class="mt-1" />
                            </div>

                            {{-- Live Preview Box --}}
                            <div class="mt-2 text-xs text-stone-500 flex items-center gap-1.5">
                                <i class="ri-information-line text-[#597928]"></i>
                                <span>Format tersimpan ke sistem: <strong class="text-stone-700 font-mono"
                                        x-text="finalPeriod"></strong></span>
                            </div>

                            <x-form.input-error :messages="$errors->get('billing_period')" class="mt-1" />
                        </div>

                        {{-- Amount Input --}}
                        <div class="input-group">
                            <div class="flex items-center justify-between">
                                <x-form.input-label for="amount" :value="__('Nominal SPP (Rp)')" />
                                <span class="text-xs text-stone-400">Contoh: 1500000</span>
                            </div>

                            <div class="relative mt-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-stone-400 font-semibold text-sm">Rp</span>
                                <x-form.text-input id="amount"
                                    class="block w-full pl-10 pr-4 text-stone-800 font-medium" type="number"
                                    name="amount" :value="old('amount')" placeholder="1500000" required
                                    autocomplete="amount" />
                            </div>

                            <p class="mt-2 text-xs text-stone-500">Nominal ini akan digenerate serentak ke semua
                                akun siswa aktif.</p>
                            <x-form.input-error :messages="$errors->get('amount')" class="mt-1" />
                        </div>

                    </div>

                    {{-- Action Buttons Footer --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-stone-100">
                        <x-link-button.secondary-link :href="route('bills.index')" class="justify-center">
                            <i class="ri-arrow-left-line mr-1"></i> Kembali ke Daftar Tagihan
                        </x-link-button.secondary-link>

                        <x-button.primary-button class="justify-center gap-2 bg-[#597928] hover:bg-[#486320]">
                            <i class="ri-flashlight-line"></i>
                            {{ __('Buat Tagihan Sekarang') }}
                        </x-button.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
