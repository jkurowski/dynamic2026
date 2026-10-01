<?php

if (! function_exists('mapCategory')) {
    function mapCategory(int $number)
    {
        switch ($number) {
            case '1':
                return "Przedszkola i szkoły podstawowe";
            case '2':
                return "Transport publiczny";
            case '3':
                return "Wylot na Autostradę A1";
            case '4':
                return "Tereny rekreacyjne, place zabaw, parki rowerowe";
            case '5':
                return "Gastronomia";
            case '6':
                return "Przychodnie publicznych i prywatne";
            case '7':
                return "Apteki";
            case '8':
                return "Kościoły";
            case '9':
                return "Markety, sklepy, drogerie";
        }
    }
}
