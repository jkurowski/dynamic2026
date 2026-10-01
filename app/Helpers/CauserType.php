<?php

if (! function_exists('causerType')) {
    function causerType(string $type)
    {
        switch ($type) {
            case 'App\Models\User':
                return "Użytkownik";
            case 'App\Models\Client':
                return "Klient";
        }
    }
}