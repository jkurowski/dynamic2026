<?php
if (!function_exists('createIframeFromButton')) {
    function createIframeFromButton($buttonHtml)
    {
        // Load the button HTML into a DOMDocument
        $dom = new DOMDocument();
        @$dom->loadHTML($buttonHtml); // Suppress warnings with @

        // Get the button element
        $button = $dom->getElementsByTagName('button')->item(0);

        if ($button) {
            // Extract data attributes
            $investment = $button->getAttribute('data-investment');
            $id = $button->getAttribute('data-id');

            // Generate the iframe HTML
            return sprintf(
                '<iframe class="iframe iframe-3destate-widget embed-responsive-item" allowfullscreen="true" src="https://360.3destate.pl/%s/%s" style="border: 0;"></iframe>',
                htmlspecialchars($investment, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($id, ENT_QUOTES, 'UTF-8')
            );
        }

        return '';
    }
}