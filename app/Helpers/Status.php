<?php

if (! function_exists('status')) {
    function status(int $number)
    {
        if ($number == 1) {
            $result = '<span data-filter="Aktywny"><i class="fe-eye"></i></span><p class="d-none">Aktywny</p>';
        } else {
            $result = '<span data-filter="Nieaktywny"><i class="fe-eye-off text-muted"></i></span><p class="d-none">Nieaktywny</p>';
        }
        return $result;
    }
}
