<?php

use App\Models\AcademicYear;
use App\Models\SchoolSetting;

use Illuminate\Support\Str;

if (!function_exists('shortNumber')) {

    function shortNumber($number)
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        }

        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }

        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }

        return $number;
    }
}


if (!function_exists('rupiah')) {

    function rupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('normalizePhone')) {
    function normalizePhone(string $phone): string
    {

        if (str_starts_with($phone, '08')) {
            return '62' .
                substr($phone, 1);
        }

        return $phone;
    }
}

if (!function_exists('titleCase')) {
    function titleCase(string $text)
    {
        $text = Str::title($text);

        return $text;
    }
}

if (!function_exists('classroomLevels')) {
    function classroomLevels()
    {
        $educationLevel = SchoolSetting::first()->education_level;

        return match ($educationLevel) {
            'SD' => [1, 2, 3, 4, 5, 6],
            'SMP' => [7, 8, 9],
            'SMA', 'SMK' => [10, 11, 12],
            default => []
        };
    }
}

if (!function_exists('activeAcademicYear')) {
    function activeAcademicYear()
    {
        return AcademicYear::where(
            'is_active',
            true
        )->first();
    }
}
