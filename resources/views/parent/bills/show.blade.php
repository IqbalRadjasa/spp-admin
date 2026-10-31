<x-app-layout>
    <div class="py-6 space-y-6">
        <div class="max-w-4xl mx-auto py-6">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-stone-200/80">

                {{-- Header Banner --}}
                <div
                    class="bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white px-8 py-6 border-b border-stone-100 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-stone-800 flex items-center gap-2">
                            <i class="ri-bill-line text-[#597928]"></i>
                            Detail Tagihan & Riwayat Eskalasi
                        </h1>
                        <p class="text-sm text-stone-500 mt-0.5">Informasi lengkap tagihan SPP dan catatan khusus siswa.
                        </p>
                    </div>

                    <x-link-button.secondary-link :href="route('parent.bills.index')" icon="ri-arrow-left-circle-line" class="">
                        Kembali
                    </x-link-button.secondary-link>
                </div>

                <div class="p-8 space-y-8">

                    {{-- Student Information Section --}}
                    <div class="bg-stone-50/60 border border-stone-200/60 rounded-2xl p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div
                                class="w-8 h-8 rounded-xl bg-[#597928]/10 text-[#597928] flex items-center justify-center font-bold">
                                <i class="ri-user-3-line"></i>
                            </div>
                            <h2 class="font-bold text-stone-800 text-base">Informasi Siswa</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div class="bg-white p-3.5 rounded-xl border border-stone-200/60 shadow-2xs">
                                <span class="block text-xs font-medium text-stone-400 mb-1">Nama Lengkap</span>
                                <span
                                    class="font-bold text-stone-800">{{ $bill->student->fullname ?? $bill->student->name }}</span>
                            </div>

                            <div class="bg-white p-3.5 rounded-xl border border-stone-200/60 shadow-2xs">
                                <span class="block text-xs font-medium text-stone-400 mb-1">Nomor Induk Siswa
                                    (NIS)</span>
                                <span class="font-mono font-bold text-[#597928]">{{ $bill->student->nis }}</span>
                            </div>

                            <div class="bg-white p-3.5 rounded-xl border border-stone-200/60 shadow-2xs">
                                <span class="block text-xs font-medium text-stone-400 mb-1">Tahun Pendaftaran</span>
                                <span
                                    class="font-semibold text-stone-700">{{ $bill->student->enrollment_year ?? '-' }}</span>
                            </div>

                            <div class="bg-white p-3.5 rounded-xl border border-stone-200/60 shadow-2xs">
                                <span class="block text-xs font-medium text-stone-400 mb-1">Status Siswa</span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ ($bill->student->is_active ?? 1) == 1 ? 'bg-[#91AC67]/15 text-[#597928]' : 'bg-rose-100 text-rose-700' }}">
                                    {{ ($bill->student->is_active ?? 1) == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Bill Information Section --}}
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-6 shadow-2xs">
                        <div class="flex items-center gap-2 mb-4">
                            <div
                                class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold">
                                <i class="ri-file-text-line"></i>
                            </div>
                            <h2 class="font-bold text-stone-800 text-base">Informasi Tagihan</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-sm">
                            <div class="p-4 bg-stone-50 rounded-xl border border-stone-100">
                                <span class="block text-xs text-stone-400 font-medium mb-1">Periode Tagihan</span>
                                <span
                                    class="text-base font-bold text-stone-800 font-mono">{{ $bill->billing_period }}</span>
                            </div>

                            <div class="p-4 bg-stone-50 rounded-xl border border-stone-100">
                                <span class="block text-xs text-stone-400 font-medium mb-1">Nominal SPP</span>
                                <span class="text-base font-bold text-[#597928]">{{ rupiah($bill->amount) }}</span>
                            </div>

                            <div
                                class="p-4 bg-stone-50 rounded-xl border border-stone-100 flex flex-col justify-between">
                                <span class="block text-xs text-stone-400 font-medium mb-1">Status Pembayaran</span>
                                <div>
                                    <span
                                        class="{{ $bill->status == 'paid' ? 'bg-[#91AC67]/15 text-[#597928]' : 'bg-rose-100 text-rose-700' }} py-1 px-2.5 text-xs font-bold rounded-lg inline-block">
                                        {{ $bill->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Escalation Status & Action --}}
                        <div class="border-t border-stone-100 pt-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                <div>
                                    <span class="text-xs font-medium text-stone-500 block">Status Eskalasi
                                        Masalah</span>
                                    <div class="mt-1">
                                        @switch($bill->escalation_status)
                                            @case('resolved')
                                                <span
                                                    class="bg-emerald-100 text-emerald-700 font-semibold py-1 px-2.5 rounded-lg text-xs">
                                                    {{ $bill->escalation_status_label }}
                                                </span>
                                            @break

                                            @case('escalated')
                                                <span
                                                    class="bg-rose-100 text-rose-700 font-semibold py-1 px-2.5 rounded-lg text-xs">
                                                    {{ $bill->escalation_status_label }}
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="bg-stone-100 text-stone-600 font-semibold py-1 px-2.5 rounded-lg text-xs">
                                                    {{ $bill->escalation_status_label ?? 'Normal' }}
                                                </span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Note's History Section --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-bold text-stone-800 text-base flex items-center gap-2">
                                <i class="ri-history-line text-stone-500"></i>
                                Riwayat Catatan Eskalasi
                            </h2>
                        </div>

                        <div class="space-y-3">
                            @forelse ($bill->escalationNotes->sortByDesc('created_at') as $note)
                                <div
                                    class="bg-stone-50/50 border border-stone-200/60 rounded-xl p-4 transition-all hover:bg-stone-50">
                                    <div class="flex items-center justify-between text-xs text-stone-400 mb-2">
                                        <span class="font-semibold text-stone-700 flex items-center gap-1">
                                            <i class="ri-user-line"></i> {{ $note->user->name }}
                                        </span>
                                        <span>{{ $note->created_at->format('d M Y - H:i') }}</span>
                                    </div>
                                    <div class="text-sm text-stone-700 pl-4 border-l-2 border-[#597928]/40">
                                        {{ $note->note }}
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="border border-dashed border-stone-200 rounded-xl p-8 text-center text-stone-400 text-sm">
                                    <i class="ri-file-list-3-line text-2xl mb-1 block opacity-50"></i>
                                    Belum ada riwayat catatan eskalasi untuk tagihan ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
