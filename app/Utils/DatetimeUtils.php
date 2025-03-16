<?php

namespace App\Utils;

use Carbon\Carbon;

class DatetimeUtils
{
    public static function defaultFormat(string $datetime): string
    {
        return Carbon::parse($datetime)->timezone(config('app.timezone'))->format(config('app.datetime.format'));
    }
}
