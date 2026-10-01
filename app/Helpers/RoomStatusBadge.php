<?php

if (! function_exists('roomStatusBadge')) {
    function roomStatusBadge(int $number)
    {
        switch ($number) {
            case '1':
                return '<span class="list-status list-status-1">Dostępne</span>';
            case '2':
                return '<span class="list-status list-status-2">Rezerwacja</span>';
            case '3':
                return '<span class="list-status list-status-3">Sprzedane</span>';
            case '4':
                return '<span class="list-status list-status-4">Wynajęte</span>';
        }
    }
}
