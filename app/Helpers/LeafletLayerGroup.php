<?php

if (! function_exists('leafletLayerGroup')) {
    function leafletLayerGroup(int $number)
    {
        switch ($number) {
            case '1':
                return 'education';
            case '2':
                return 'transport';
            case '3':
                return 'highway';
            case '4':
                return 'park';
            case '5':
                return 'restaurant';
            case '6':
                return 'clinic';
            case '7':
                return 'pharmacy';
            case '8':
                return 'church';
            case '9':
                return 'store';
        }
    }
}
