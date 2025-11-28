<?php

namespace App\Helpers;

class NumberHelper
{
    public static function formatCurrency($amount, $withTooltip = true)
    {
        $value = abs($amount);
        $formatted = '';
        $terbilang = '';
        
        if ($value >= 1000000000000) { // Trilyun
            $formatted = 'Rp ' . number_format($value / 1000000000000, 1) . 'T';
            $number = $value / 1000000000000;
        } elseif ($value >= 1000000000) { // Milyar
            $formatted = 'Rp ' . number_format($value / 1000000000, 1) . 'M';
            $number = $value / 1000000000;
        } elseif ($value >= 1000000) { // Juta
            $formatted = 'Rp ' . number_format($value / 1000000, 1) . 'Jt';
            $number = $value / 1000000;
        } else {
            $formatted = 'Rp ' . number_format($value, 0, ',', '.');
            $number = $value;
        }
        
        if ($withTooltip) {
            $terbilang = self::terbilang($amount);
            return [
                'formatted' => $formatted,
                'terbilang' => $terbilang
            ];
        }
        
        return $formatted;
    }
    
    public static function terbilang($number)
    {
        $number = abs($number);
        $words = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 
            'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'
        ];
        
        if ($number < 12) {
            return $words[$number];
        } elseif ($number < 20) {
            return $words[$number - 10] . ' belas';
        } elseif ($number < 100) {
            return $words[intval($number / 10)] . ' puluh ' . self::terbilang($number % 10);
        } elseif ($number < 200) {
            return 'seratus ' . self::terbilang($number - 100);
        } elseif ($number < 1000) {
            return $words[intval($number / 100)] . ' ratus ' . self::terbilang($number % 100);
        } elseif ($number < 2000) {
            return 'seribu ' . self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::terbilang(intval($number / 1000)) . ' ribu ' . self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::terbilang(intval($number / 1000000)) . ' juta ' . self::terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            return self::terbilang(intval($number / 1000000000)) . ' milyar ' . self::terbilang($number % 1000000000);
        } else {
            return self::terbilang(intval($number / 1000000000000)) . ' trilyun ' . self::terbilang($number % 1000000000000);
        }
    }
}
