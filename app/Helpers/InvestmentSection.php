<?php

if (! function_exists('investmentSection')) {
    function investmentSection($array, $id, $element)
    {
        foreach ($array as $a) {
            if ($a['id'] == $id) {
                if ($element == 'file') {
                    return '/investment/sections/'.$a[$element];
                } else {
                    return $a[$element];
                }
            }
        }
    }
}
