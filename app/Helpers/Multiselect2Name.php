<?php

if (!function_exists('multiselect2name')) {
    function multiselect2name($values): string
    {
        // Check if $values is null, return empty string
        if ($values === null) {
            return '';
        }

        // Convert JSON string to array if $values is a string
        if (is_string($values)) {
            $selectedValues = json_decode($values);
            $values = is_array($selectedValues) ? array_map('intval', $selectedValues) : [];
        }

        // If $values is already an array, convert its elements to integers
        if (is_array($values)) {
            $values = array_map('intval', $values);
        } else {
            // If $values is neither a string nor an array, return an empty string
            return '';
        }

        // Define your mappings here
        $mappings = [
            '1' => 'Północ',
            '2' => 'Południe',
            '3' => 'Wschód',
            '4' => 'Zachód',
            '5' => 'Północny wschód',
            '6' => 'Północny zachód',
            '7' => 'Południowy wschód',
            '8' => 'Południowy zachód'
        ];

        $names = [];

        // Loop through the values and map them to names
        foreach ($values as $value) {
            if (isset($mappings[$value])) {
                $names[] = $mappings[$value];
            }
        }

        // Return the names as a comma-separated string
        return implode(', ', $names);
    }
}