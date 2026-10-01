<?php

if (! function_exists('offerStatus')) {
    function offerStatus(int $number)
    {
        switch ($number) {
            case '1':
                return "Wysłana";
            case '2':
                return "Szkic";
            case '3':
                return "Przeczytana";
        }
    }
}