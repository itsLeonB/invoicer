<?php

namespace App\Utils;

class MoneyUtils
{
    public static function format(int $money): string
    {
        return number_format($money, 0, '.', ',');
    }
}
