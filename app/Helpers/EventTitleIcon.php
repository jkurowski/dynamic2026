<?php
if (! function_exists('eventTitleIcon')) {
    function eventTitleIcon(int $number)
    {
        $events = Config::get('events');
        $event_index = array_search($number, array_column($events, 'id'));
        $event = $events[$event_index];
        return '<i class="'.$event['icon'].'"></i> '.$event['name'];
    }
}
