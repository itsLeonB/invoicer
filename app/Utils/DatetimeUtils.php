<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DatetimeUtils
{
    public static function defaultFormat(string $datetime): string
    {
        try {
            return Carbon::parse($datetime)->timezone(config('app.timezone'))->format(config('app.datetime.format'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $datetime;
        }
    }
}
