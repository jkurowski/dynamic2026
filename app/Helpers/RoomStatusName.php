<?php
if (! function_exists('roomStatusName')) {
    function roomStatusName(int $number)
    {
        switch ($number) {
            case 1:
                return 'available';
            case 2:
                return 'reserved';
            case 3:
                return 'sold';
        }
    }
}