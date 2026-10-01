<?php

use Illuminate\Support\Str;

if (! function_exists('eventType')) {
    function eventType(int $number)
    {
        $events = Config::get('events');
        $event_index = array_search($number, array_column($events, 'id'));
        $event = $events[$event_index];
        return '<span class="table-event table-event-'.$number.'"><i class="'.$event["icon"].'"></i> '.$event["name"].'</span>';
    }
}
