<?php

use App\Models\Contract;
use Carbon\Carbon;

if (! function_exists('convertRegex2Date')) {
    function convertRegex2Date($format): array|string
    {
        $locale = 'pl_PL'; // Polish locale

        setlocale(LC_TIME, $locale);
        Carbon::setLocale($locale);

        $date = Carbon::now();
        $nextNumber = Contract::count() + 1;

        $format = str_replace('[D]', ucfirst($date->isoFormat('ddd')), $format);
        $format = str_replace('[d]', $date->isoFormat('DD'), $format);
        $format = str_replace('[M]', ucfirst($date->isoFormat('MMM')), $format);
        $format = str_replace('[m]', $date->isoFormat('MM'), $format);
        $format = str_replace('[Y]', $date->isoFormat('YYYY'), $format);
        $format = str_replace('[y]', $date->isoFormat('YY'), $format);
        $format = str_replace('[n]', $nextNumber, $format);
        return str_replace('[/]', '/', $format);
    }
}
