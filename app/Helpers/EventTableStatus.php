<?php

use Illuminate\Support\Str;

if (! function_exists('eventTableStatus')) {
    function eventTableStatus(int $status)
    {
        if($status == 1)
        {
            return '<span class="table-event-status-0"><i class="fe-check-square"></i></span>';
        } else {
            return '<span class="table-event-status-1"><i class="fe-square"></i></span>';
        }
    }
}
