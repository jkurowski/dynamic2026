<?php

if (! function_exists('squareMeterPrice')) {
    function squareMeterPrice($price, $area)
    {
        return round($price / $area, 2);
    }
}
