<?php

if (!function_exists('getFromUtm')) {
    function getFromUtm($array, $param)
    {
        foreach ($array as $item) {
            if ($item['argument'] == $param) {
                if ($param == 'fbclid' || $param == 'gclid') {
                    return '(' . $param . ') ' . $item['value'];
                } else {
                    return $item['value'];
                }
            }
        }

        return null; // Return null if the value is not found
    }
}

