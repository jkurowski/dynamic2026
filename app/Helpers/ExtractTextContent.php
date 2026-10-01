<?php

if (!function_exists('extract_text_content')) {
    function extract_text_content($html)
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

        // Find all <div> elements with specific styles
        $divElements = $dom->getElementsByTagName('div');

        $extractedContent = '';

// Iterate over each <div> element
        foreach ($divElements as $divElement) {
            if ($divElement->getAttribute('style') === 'padding-top:15px;padding-bottom:15px') {
                $childNodes = $divElement->childNodes;
                foreach ($childNodes as $childNode) {
                    $extractedContent .= $dom->saveHTML($childNode);
                }
                break;
            }
        }
        echo $extractedContent;
    }
}
