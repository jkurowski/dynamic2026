<?php

if (! function_exists('cords')) {
    if (! function_exists('cords')) {
        function cords($string)
        {
            $pattern = '/coords="([^"]*)"/';
            preg_match($pattern, $string, $matches);
            return $matches[1];
        }
    }
}
