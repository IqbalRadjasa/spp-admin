<x-app-layout>
    <div class="py-6 space-y-6">

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Daftar Tagihan</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ url()->current() }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">

                    <x-form.select-input name="academic_year_id" class="md:w-auto">
                        <option value="">Semua Tahun Akademik</option>
                        @foreach ($academicYears as $ay)
                            <option value="{{ $ay->id }}"
                                {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                {{ $ay->name }}
                            </option>
                        @endforeach
                    </x-form.select-input>

                    <div class="flex items-center gap-2">
                        <x-button.primary-button type="submit" class="w-full sm:w-auto justify-center">
                            <i class="ri-filter-3-line mr-1"></i> Filter
                        </x-button.primary-button>

                        @if (request()->hasAny(['academic_year_id']))
                            <x-link-button.secondary-link :href="url()->current()">
                                <i class="ri-refresh-line"></i> Reset
                            </x-link-button.secondary-link>
                        @endif
                    </div>
                </form>

                <x-table :headers="['Periode', 'Total Siswa', 'Nominal', 'Tahun Akademik', 'Tanggal Buat', 'Aksi']" :empty="$bills->isEmpty()">
                    @foreach ($bills as $bill)
                        <x-table.tr>
                            <x-table.td>
                                {{ $bill->billing_period }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.badge variant="olive">
                                    {{ $bill->total_students }}
                                </x-table.badge>
                            </x-table.td>

                            <x-table.td>
                                {{ rupiah($bill->amount) }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $bill->academicYear->name }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                {{ $bill->created_at }}
                            </x-table.td>

                            <x-table.td>
                                <x-button.primary-button class="gap-1" type="button"
                                    x-on:click="$dispatch('open-bill-modal', {
                                                academic_year_id: '{{ $bill->academic_year_id }}',
                                                billing_period: '{{ $bill->billing_period }}',
                                                month: '{{ $bill->month_number }}',
                                                year: '{{ $bill->year }}',
                                                amount: '{{ $bill->amount }}',
                                            })"><i
                                        class="ri-pencil-line"></i> Edit Tagihan
                                </x-button.primary-button>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $bills->links('vendor.pagination.default') }}
            </div>
        </div>

        {{-- Classroom's Form Modal --}}
        <x-modal name="bill-form-modal" maxWidth="md">
            <div x-data="{
                form: {
                    academic_year_id: @js(old('academic_year_id', '')),
                    billing_period: @js(old('billing_period', '')),
                    month: @js(old('month', '')),
                    year: @js(old('year', '')),
                    amount: @js(old('amount', '')),
                }
            }" {{-- Re-open modal automatically if validation fails --}}
                @if ($errors->any()) x-init="$nextTick(() => $dispatch('open-modal', 'bill-form-modal'))" @endif
                x-on:open-bill-modal.window="
                    form.academic_year_id = $event.detail.academic_year_id;
                    form.billing_period = $event.detail.billing_period;
                    form.month = $event.detail.month;
                    form.year = $event.detail.year;
                    form.amount = $event.detail.amount;
                    $dispatch('open-modal', 'bill-form-modal');
                ">

                <form action="{{ route('bills.update-batch') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="academic_year_id" x-model="form.academic_year_id">
                    <input type="hidden" name="billing_period" x-model="form.billing_period">

                    <div class="p-6">
                        <h2 class="text-lg font-bold uppercase mb-4">Edit Tagihan</h2>

                        <div class="space-y-4">
                            {{-- Month --}}
                            <div class="input-group">
                                <x-form.input-label for="month" :value="__('Bulan')" />
                                <x-form.select-input name="month" x-model="form.month">
                                    <option value="">Semua Bulan</option>
                                    @foreach ($months as $num => $name)
                                        <option value="{{ $num }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('month')" class="mt-1" />
                            </div>

                            {{-- Year --}}
                            <div class="input-group">
                                <x-form.input-label for="year" :value="__('Tahun')" />
                                <x-form.select-input x-model="form.year" name="year">
                                    <option value="">Semua Bulan</option>
                                    @if ($activeAcademicYear)
                                        <option value="{{ $year1 }}">{{ $year1 }}</option>
                                        <option value="{{ $year2 }}">{{ $year2 }}</option>
                                    @else
                                        <option value="{{ now()->format('Y') }}">{{ now()->format('Y') }}</option>
                                    @endif
                                </x-form.select-input>
                                <x-form.input-error :messages="$errors->get('year')" class="mt-1" />
                            </div>


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
                                        name="amount" x-model="form.amount" placeholder="1500000" required
                                        autocomplete="amount" />
                                </div>

                                <p class="mt-2 text-xs text-stone-500">Nominal ini akan digenerate serentak ke semua
                                    akun siswa aktif.</p>
                                <x-form.input-error :messages="$errors->get('amount')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-[#FAF8F5] border-t border-stone-200/80 flex justify-end gap-2">
                        <x-button.secondary-button type="button"
                            x-on:click="$dispatch('close-modal', 'bill-form-modal')">
                            Batal
                        </x-button.secondary-button>

                        <x-button.primary-button type="submit" class="bg-[#597928] hover:bg-[#6E3511]">
                            Simpan Perubahan
                        </x-button.primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>
