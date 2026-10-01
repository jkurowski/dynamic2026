<?php

if (! function_exists('investmentStatus')) {
    function investmentStatus(int $number)
    {
        switch ($number) {
            case '1':
                return "Inwestycja aktualna";
            case '2':
                return "Inwestycja zrealizowana";
            case '3':
                return "Inwestycja planowana";
            case '4':
                return "Inwestycja ukryta";
        }
    }
}
