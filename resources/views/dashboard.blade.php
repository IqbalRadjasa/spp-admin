<x-app-layout>
    <div class="py-6">
        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Dashboard</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex gap-2">
                        <div class="w-1/4">
                            <x-widget title="Total Students" :value="$total_students" icon="ri-graduation-cap-line" />
                        </div>
                        <div class="w-1/4">
                            <x-widget title="Unpaid Bills" :value="$unpaid_bills" icon="ri-file-warning-line" />
                        </div>
                        <div class="w-1/4">
                            <x-widget title="Payment This Month" :value="$payments_this_month" icon="ri-wallet-3-line" />
                        </div>
                        <div class="w-1/4">
                            <x-widget title="Income This Month" :value="'Rp ' . shortNumber($income_this_month)" icon="ri-cash-line" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
