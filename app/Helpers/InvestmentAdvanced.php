<?php

if (! function_exists('investmentAdvanced')) {
    function investmentAdvanced(int $number)
    {
        switch ($number) {
            case '1':
                return "Przedsprzedaż";
            case '2':
                return "Realizacja";
            case '3':
                return "Realizacja 40%";
            case '4':
                return "Realizacja 60%";
            case '5':
                return "Realizacja 80%";
            case '6':
                return "Realizacja 100%";
            case '7':
                return "Gotowe do odbioru";
        }
    }
}
