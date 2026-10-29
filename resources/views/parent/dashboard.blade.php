<x-app-layout>
    <div class="py-6 space-y-6">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#597928] to-[#435c1d] p-6 text-white shadow-sm">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none">
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-[#FCECD8] backdrop-blur-sm">
                        <i class="ri-heart-line"></i> Portal Orang Tua
                    </span>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Selamat Datang, {{ $studentParent->nickname ?? $studentParent->fullname }}! 👋
                    </h2>
                    <p class="text-stone-100/80 text-sm">
                        Pantau status pembayaran, tagihan SPP, dan perkembangan anak Anda dengan mudah di sini.
                    </p>
                </div>
                <div
                    class="flex items-center gap-3 bg-black/10 px-4 py-2.5 rounded-xl backdrop-blur-sm border border-white/10">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center text-lg text-[#FCECD8]">
                        <i class="ri-calendar-event-line"></i>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-stone-200">Hari Ini</div>
                        <div class="text-sm font-semibold text-white">{{ now()->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-dashboard-line"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-2xl text-stone-800 uppercase tracking-tight">Dashboard</h1>
                    </div>
                </div>

                <!-- Child Selector Dropdown (If parent has multiple children) -->
                {{-- @if ($children->count() > 1)
                    <form method="GET" action="{{ route('parent.dashboard') }}" class="flex items-center gap-2">
                        <label for="child_id" class="text-sm font-medium text-stone-700 whitespace-nowrap">Pilih
                            Anak:</label>
                        <select name="child_id" id="child_id" onchange="this.form.submit()"
                            class="form-select text-sm rounded-lg border-stone-300 focus:border-[#597928] focus:ring-[#597928]">
                            @foreach ($children as $child)
                                <option value="{{ $child->id }}"
                                    {{ $activeChild->id == $child->id ? 'selected' : '' }}>
                                    {{ $child->fullname ?? $child->name }} (NIS: {{ $child->nis }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <div class="bg-stone-100 px-3 py-1.5 rounded-lg text-sm font-medium text-stone-700">
                        Anak: <span
                            class="text-stone-900 font-semibold">{{ $activeChild->fullname ?? $activeChild->name }}</span>
                    </div>
                @endif --}}
            </div>
        </div>

        <!-- Student Quick Profile Banner -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-stone-200 flex items-center gap-4">
            <div
                class="w-14 h-14 rounded-full bg-[#597928]/10 text-[#597928] flex items-center justify-center font-bold text-xl overflow-hidden border border-stone-200">
                @if ($activeChild->avatar)
                    <img src="{{ asset('storage/' . $activeChild->avatar) }}" alt="Avatar"
                        class="w-full h-full object-cover">
                @else
                    {{ substr($activeChild->fullname ?? $activeChild->name, 0, 2) }}
                @endif
            </div>
            <div>
                <h2 class="text-lg font-bold text-stone-900">{{ $activeChild->fullname ?? $activeChild->name }}</h2>
                <p class="text-xs text-stone-500">NIS: {{ $activeChild->nis }} | Kelas:
                    {{ $activeChild->classroom->display_name ?? 'Belum ditentukan' }}</p>
            </div>
        </div>

        <!-- Child Widgets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-widget title="Tagihan Belum Lunas" :value="$unpaidBillsCount" icon="ri-file-warning-line" />
            <x-widget title="Tagihan Lunas" :value="$paidBillsCount" icon="ri-checkbox-circle-line" />
            <x-widget title="Total Tunggakan" :value="'Rp ' . number_format($totalDebt)" icon="ri-wallet-3-line" />
        </div>

        <!-- Recent Payments Table for This Child -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-stone-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-lg text-stone-800">
                        Riwayat Pembayaran Terakhir
                    </h2>
                </div>


                <x-table :headers="['Kode Pembayaran', 'Metode', 'Nominal', 'Dibayar pada']" :empty="$recentPayments->isEmpty()">
                    @foreach ($recentPayments as $payment)
                        <x-table.tr>
                            <x-table.td class="text-[#597928] font-bold">
                                {{ $payment->payment_code }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $payment->paymentMethod->name }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                {{ rupiah($payment->amount_paid) }}
                            </x-table.td>

                            <x-table.td>
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, H:i') }}
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>
            </div>
        </div>

    </div>
</x-app-layout>
