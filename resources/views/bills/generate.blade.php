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
                    <h1>Generate Tagihan</h1>

                    <form action="{{ route('bills.generate') }}" method="POST">
                        @csrf

                        <div>
                            <label>Periode Tagihan</label>

                            <input type="month" name="billing_period" required>
                        </div>

                        <div>
                            <label>Nominal SPP</label>

                            <input type="number" name="amount" required>
                        </div>

                        <button type="submit">
                            Generate
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
