<?php

if (! function_exists('lock')) {
    function lock(int $number)
    {
        if ($number == 1) {
            $result = '<span data-filter="Prywatne"><i class="fe-lock"></i></span><p class="d-none">Prywatne</p>';
        } else {
            $result = '<span data-filter="Dla wszystkich"><i class="fe-unlock text-muted"></i></span><p class="d-none">Dla wszystkich</p>';
        }
        return $result;
    }
}
