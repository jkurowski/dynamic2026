<?php

if (! function_exists('mime2icon')) {
    function mime2icon($mime_type)
    {
        $icon_classes = array(
// Media
            'image' => 'las la-file-image',
            'audio' => 'las la-file-audio',
            'video' => 'las la-file-video',
            'catalog' => 'las la-folder',
// Documents
            'application/pdf' => 'las la-file-pdf',
            'application/msword' => 'las la-file-word',
            'application/vnd.ms-word' => 'las la-file-word',
            'application/vnd.oasis.opendocument.text' => 'las la-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'las la-file-word',
            'application/vnd.ms-excel' => 'las la-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'las la-file-excel',
            'application/vnd.oasis.opendocument.spreadsheet' => 'las la-file-excel',
            'application/vnd.ms-powerpoint' => 'las la-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml' => 'las la-file-powerpointo',
            'application/vnd.oasis.opendocument.presentation' => 'las la-file-powerpoint',
            'text/plain' => 'las la-file-alt',
            'text/html' => 'las la-file-code',
            'application/json' => 'las la-file-code',
// Archives
            'application/gzip' => 'las la-file-archive',
            'application/zip' => 'las la-file-archive',
        );
        foreach ($icon_classes as $text => $icon) {
            if (strpos($mime_type, $text) === 0) {
                return $icon;
            }
        }
        return 'las la-file-alt';
    }
}
