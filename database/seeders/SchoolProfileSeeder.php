<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolProfile::create([
            'school_name' => 'My School',
            'education_level' => 'SMK'
        ]);
    }
}
