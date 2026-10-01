<?php

if (!function_exists('getWindowDirections')) {
    function getWindowDirections($windowField)
    {
        $directions = [
            '1' => 'Północ',
            '2' => 'Południe',
            '3' => 'Wschód',
            '4' => 'Zachód',
            '5' => 'Północny wschód',
            '6' => 'Północny zachód',
            '7' => 'Południowy wschód',
            '8' => 'Południowy zachód',
        ];

        if (empty($windowField)) {
            return null;
        }

        // Decode JSON if it's a string
        $selected = is_array($windowField) ? $windowField : json_decode($windowField, true);

        return collect($selected)
            ->map(fn($item) => $directions[$item] ?? null)
            ->filter()
            ->implode(', ');
    }
}
