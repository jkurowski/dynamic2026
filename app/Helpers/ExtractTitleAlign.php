<?php

if (!function_exists('extract_title_align')) {
    function extract_title_align($html)
    {
        // Create a DOMDocument instance
        $dom = new DOMDocument();

        // Load HTML content
        $dom->loadHTML($html);

        // Get the first h2 element
        $h2 = $dom->getElementsByTagName('h2')->item(0);

        // If h2 element exists
        if ($h2) {
            // Get the style attribute
            $style = $h2->getAttribute('style');

            // Parse the style attribute to extract text-align value
            preg_match('/text-align:\s*([a-zA-Z]+);/', $style, $matches);

            // If matches found, return the text-align value, else return null
            return $matches[1] ?? "left";
        }

        // Return null if h2 element is not found
        return "left";
    }
}