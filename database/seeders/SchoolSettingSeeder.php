<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolSetting::create([
            'school_name' => 'My School',
            'education_level' => 'SMK'
        ]);
    }
}
