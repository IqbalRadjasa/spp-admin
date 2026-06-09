<?php

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
