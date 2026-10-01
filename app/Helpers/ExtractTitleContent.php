<?php

if (!function_exists('extract_title_content')) {
    function extract_title_content($html)
    {
        // Decode HTML entities to ensure proper encoding
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

        // Load the HTML string into a DOMDocument
        $dom = new DOMDocument();
        // Suppress errors for badly formed HTML
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        // Clear any previous errors
        libxml_clear_errors();

        // Find all <h2> elements
        $h2Elements = $dom->getElementsByTagName('h2');

        // Initialize a variable to store the content inside <h2> tags
        $h2ContentString = '';

        // Iterate over each <h2> element
        foreach ($h2Elements as $h2Element) {
            // Get the text content inside <h2> and concatenate it to the string
            $h2ContentString .= $h2Element->textContent . ', ';
        }

        // Remove the trailing comma and space
        $h2ContentString = rtrim($h2ContentString, ', ');

        // Apply htmlspecialchars to the string to encode special characters
        $encodedContent = htmlspecialchars($h2ContentString);

        // Return the encoded content
        return $encodedContent;
    }
}