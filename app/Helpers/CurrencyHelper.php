<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount, $symbol = null)
    {
        $symbol = $symbol ?? \App\Helpers\SettingsHelper::currencySymbol();
        return $symbol . ' ' . number_format($amount, 2);
    }
    
    public static function getSymbol()
    {
        return \App\Helpers\SettingsHelper::currencySymbol();
    }
}