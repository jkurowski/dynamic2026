<?php

if (! function_exists('page_type')) {
    function page_type(int $number)
    {
        if ($number == 1) {
            $result = 'Strona';
        } else {
            $result = 'Adres url';
        }
        return $result;
    }
}
