<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = now()->year;
        $endYear = $startYear + 1;
        $name = "{$startYear} / {$endYear}";

        AcademicYear::updateOrCreate(
            ['name' => $name],
            ['is_active' => true]
        );
    }
}