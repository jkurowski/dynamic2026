<?php

if (! function_exists('required')) {
    function required(int $number)
    {
        if ($number == 1) {
            $result = '<i class="fe-power text-success"></i>';
        } else {
            $result = '<i class="fe-power text-muted"></i>';
        }
        return $result;
    }
}
