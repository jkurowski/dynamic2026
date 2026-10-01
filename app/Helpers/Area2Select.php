<?php

if (! function_exists('area2Select')) {
    function area2Select($range): string
    {
        $var = explode(',', $range);
        $html = '';
        foreach ($var as $pozycja) {
            $html .= '<option value="'.$pozycja.'"';
            if (request()->input('area') == $pozycja) {
                $html .= ' selected';
            }
            $html .= '>';
            $html .= $pozycja.' m²';
            $html .= '</option>';
        }
        return $html;
    }
}
