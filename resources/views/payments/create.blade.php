<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Pembayaran SPP</h1>

                    <p>
                        Nama Siswa:
                        {{ $bill->student->name }}
                    </p>

                    <p>
                        Periode:
                        {{ $bill->billing_period }}
                    </p>

                    <p>
                        Nominal:
                        Rp {{ number_format($bill->amount) }}
                    </p>

                    <form action="{{ route('payments.store', $bill->id) }}" method="POST">
                        @csrf

                        <div>
                            <label>Metode Pembayaran</label>

                            <select name="payment_method_id">
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label>Tanggal Bayar</label>

                            <input type="datetime-local" name="paid_at">
                        </div>

                        <div>
                            <label>Jumlah Bayar</label>

                            <input type="number" name="amount_paid" value="{{ $bill->amount }}">
                        </div>

                        <div>
                            <label>Catatan</label>

                            <textarea name="notes"></textarea>
                        </div>

                        <button type="submit">
                            Simpan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
