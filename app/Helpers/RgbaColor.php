<?php

if (!function_exists('rgbaColor')) {
    function rgbaColor($color, $opacity): string
    {
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        return "rgba($r, $g, $b, $opacity)";
    }
}
