<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Daftar Tagihan</h1>

                    <table id="billsTable" class="min-w-full">
                        <tr>
                            <th>Siswa</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                        @foreach ($bills as $bill)
                            <tr>
                                <td>{{ $bill->student->name }}</td>

                                <td>{{ $bill->billing_period }}</td>

                                <td>{{ number_format($bill->amount) }}</td>

                                <td>{{ $bill->status }}</td>

                                <td>
                                    @if ($bill->status == 'unpaid')
                                        <a class="bg-gray-200 px-5 py-2 rounded transition hover:bg-gray-300"
                                            href="{{ route('payments.create', $bill->id) }}">
                                            Bayar
                                        </a>
                                    @else
                                        Sudah Lunas
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
