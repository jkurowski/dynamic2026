<?php

if (!function_exists('sekcja')) {
    /** Edytowalna sekcja frontu (config/sekcje.php + tabela sekcje) */
    function sekcja(string $klucz): \App\Models\Sekcja
    {
        return \App\Models\Sekcja::dlaKlucza($klucz);
    }
}
