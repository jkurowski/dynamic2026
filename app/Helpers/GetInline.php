<?php

if (! function_exists('getInline')) {
    function getInline($array, $id, $element)
    {
        foreach ($array as $a) {
            if ($a['id'] == $id) {
               if ($element == 'file') {
                    return '/uploads/inline/'.$a[$element];
                } else {

                    return $a[$element];
                }
            }
        }
    }
}
