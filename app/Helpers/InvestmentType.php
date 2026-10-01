<?php

if (! function_exists('investmentType')) {
    function investmentType(int $number)
    {
        switch ($number) {
            case '1':
                return "Inwestycja osiedlowa";
            case '2':
                return "Inwestycja budynkowa";
            case '3':
                return "Inwestycja z domami";
            case '4':
                return "Inna oferta";
        }
    }
}
