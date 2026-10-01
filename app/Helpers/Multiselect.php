<?php

if (! function_exists('multiselect')) {
    function multiselect($jsondata)
    {
        if ($jsondata) {
            $selectedValues = json_decode($jsondata, true); // Decode as an associative array
            if (is_array($selectedValues)) {
                return array_map('intval', $selectedValues);
            }
        }
        return [];
    }
}
