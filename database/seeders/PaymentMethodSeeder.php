<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'name' => 'Tunai',
                'code' => 'tunai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transfer/lainnya',
                'code' => 'transfer_atau_lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
