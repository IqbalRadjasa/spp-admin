<style>
    @page {
        size: A4;
        margin: 8mm;
        /* Slightly tighter margins to give more room */
    }

    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide navigation, headers, sidebars, and action buttons */
        header,
        nav,
        aside,
        footer,
        .no-print {
            display: none !important;
        }

        /* Completely reset body and html for single-page print */
        body,
        html {
            background: white !important;
            color: black !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow: hidden !important;
            /* Prevents overflow into page 2 */
        }

        .max-w-4xl {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .receipt-wrapper {
            border: none !important;
            box-shadow: none !important;
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Force receipt to pack tightly and prevent page breaks */
        .receipt {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 4mm !important;
            box-shadow: none !important;
            border: none !important;
            page-break-inside: avoid !important;
            page-break-after: avoid !important;
            page-break-before: avoid !important;
        }

        /* Reduce vertical gaps inside print mode to guarantee 1 page */
        .print-tight-mb {
            margin-bottom: 12px !important;
        }

        .print-tight-py {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
    }
</style>

<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Top Navigation / Action Bar (Hidden on Print) --}}
        <div class="flex flex-col sm:flex-row md:items-center md:justify-between pb-6 gap-2 no-print">
            <div>
                <h1 class="font-extrabold text-2xl text-stone-800 tracking-tight">Cetak Bukti Pembayaran</h1>
                <p class="text-xs text-stone-500 mt-0.5">Pratinjau kuitansi resmi transaksi pembayaran SPP.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <x-link-button.secondary-link :href="route('reports.payment-report')" icon="ri-arrow-left-circle-line" class="">
                    Kembali
                </x-link-button.secondary-link>

                <x-link-button.primary-link :href="route('payments.download-receipt', $payment)">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ __('Download PDF Kuitansi') }}
                </x-link-button.primary-link>
            </div>
        </div>

        {{-- Receipt Printable Container --}}
        <div class="receipt-wrapper bg-white overflow-hidden rounded-2xl shadow-xs border border-stone-200/80 relative">
            {{-- Decorative Top Accent Line --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#91AC67] to-[#597928] no-print"></div>

            <div class="overflow-x-auto">
                <div class="receipt p-6 sm:p-8 w-full max-w-3xl mx-auto bg-white">

                    {{-- Header Banner --}}
                    <div
                        class="bg-[#597928] text-white text-lg sm:text-xl font-extrabold text-center py-4 sm:py-5 rounded-xl shadow-xs tracking-wide">
                        <h1>BUKTI PEMBAYARAN SPP SEKOLAH</h1>
                    </div>

                    {{-- School Info & Logo (Unified & Clean) --}}
                    <div
                        class="text-center py-6 border-b border-stone-200/60 mb-8 flex flex-col items-center justify-center">
                        @if (isset($school) && $school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo"
                                class="h-14 sm:h-16 w-auto object-contain mb-3">
                        @else
                            <div
                                class="w-12 h-12 rounded-xl bg-[#597928]/10 flex items-center justify-center text-[#597928] mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif

                        <h2 class="text-lg font-bold text-stone-800">{{ $school->school_name ?? 'Your School Name' }}
                        </h2>
                        <p class="text-xs text-stone-500 mt-1 max-w-md mx-auto leading-relaxed">
                            {{ $school->address ?? 'Alamat sekolah belum diatur pada menu Pengaturan Profil Sekolah.' }}
                        </p>
                    </div>

                    {{-- Meta Details: Receipt Code & Date --}}
                    <div
                        class="flex justify-between items-start mb-8 bg-stone-50/80 p-4 rounded-xl border border-stone-200/60">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-500 block">Status
                                Pembayaran</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 mt-1">
                                Lunas (Paid)
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-500 block">Kode
                                Transaksi</span>
                            <span
                                class="font-mono font-bold text-stone-800 bg-white px-3 py-1 rounded-lg border border-stone-200/80 shadow-2xs mt-1 inline-block text-xs sm:text-sm">
                                {{ $payment->payment_code }}
                            </span>
                        </div>
                    </div>

                    {{-- Student & Transaction Information Grid --}}
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm mb-8 p-6 bg-gradient-to-br from-white via-[#FCECD8]/20 to-[#91AC67]/10 rounded-xl border border-stone-200/70">
                        <div>
                            <span class="text-stone-500 block text-xs uppercase font-bold tracking-wider">Nama
                                Siswa</span>
                            <span
                                class="font-semibold text-stone-800 text-base">{{ $payment->bill->student->fullname }}</span>
                        </div>

                        <div>
                            <span class="text-stone-500 block text-xs uppercase font-bold tracking-wider">Nomor Induk
                                Siswa (NIS)</span>
                            <span
                                class="font-semibold text-stone-800 text-base font-mono">{{ $payment->bill->student->nis ?? '-' }}</span>
                        </div>

                        <div>
                            <span class="text-stone-500 block text-xs uppercase font-bold tracking-wider">Periode
                                Tagihan</span>
                            <span
                                class="font-semibold text-stone-800 text-base">{{ $payment->bill->billing_period }}</span>
                        </div>

                        <div>
                            <span class="text-stone-500 block text-xs uppercase font-bold tracking-wider">Tanggal
                                Pembayaran</span>
                            <span class="font-semibold text-stone-800 text-base">{{ $payment->paid_at }}</span>
                        </div>

                        <div>
                            <span class="text-stone-500 block text-xs uppercase font-bold tracking-wider">Metode
                                Pembayaran</span>
                            <span
                                class="font-semibold text-stone-800 text-base">{{ $payment->paymentMethod->name }}</span>
                        </div>

                        <div>
                            <span class="text-stone-500 block text-xs uppercase font-bold tracking-wider">Catatan</span>
                            <span class="font-medium text-stone-700 text-sm italic">{{ $payment->notes ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Table Payment Information --}}
                    <div class="mb-10 overflow-hidden rounded-xl border border-stone-200">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-[#91AC67]/20 text-stone-800 text-xs uppercase font-extrabold tracking-wider border-b border-stone-200">
                                    <th class="border-r border-stone-200 px-4 py-3 text-center w-16">No</th>
                                    <th class="border-r border-stone-200 px-6 py-3">Rincian / Keterangan</th>
                                    <th class="px-6 py-3 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-stone-200">
                                <tr>
                                    <td
                                        class="border-r border-stone-200 px-4 py-3 text-center text-stone-500 font-medium">
                                        1</td>
                                    <td class="border-r border-stone-200 px-6 py-3 text-stone-800 font-medium">
                                        Pembayaran Iuran SPP Periode {{ $payment->bill->billing_period }}</td>
                                    <td class="px-6 py-3 text-right font-semibold text-stone-800">
                                        {{ rupiah($payment->bill->amount) }}</td>
                                </tr>
                                <tr class="bg-stone-100 font-bold text-stone-900">
                                    <td class="border-r border-stone-200 px-4 py-3 text-center" colspan="2">Total
                                        Pembayaran</td>
                                    <td class="px-6 py-3 text-right text-[#597928] text-base">
                                        {{ rupiah($payment->bill->amount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Signature Section --}}
                    <div class="flex justify-between items-end pt-8 mt-12 border-t border-stone-200 text-sm">
                        <div class="text-stone-500 text-xs max-w-xs">
                            <p>Dokumen ini dicetak secara otomatis melalui sistem.</p>
                            <p>Sah dan tidak memerlukan stempel basah apabila diverifikasi.</p>
                        </div>
                        <div class="text-center">
                            <p class="text-stone-600 mb-14">Petugas / Bendahara Sekolah</p>
                            <p class="font-bold text-stone-800 border-b border-stone-400 pb-1 px-8">(
                                ______________________ )</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // document.title = "Kuitansi-SPP-{{ $payment->payment_code }}-{{ $payment->bill->student->name }}";

        @if (session('auto_print'))
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    window.print();
                }, 500);
            });
        @endif
    </script>
</x-app-layout>
