<?php

if (! function_exists('room2Select')) {
    function room2Select($range): string
    {
        $var = explode(',', $range);
        $html = '';
        foreach ($var as $pozycja) {
            $html .= '<option value="'.$pozycja.'"';
            if (request()->input('area') == $pozycja) {
                $html .= ' selected';
            }
            $html .= '>';
            $html .= $pozycja;
            $html .= '</option>';
        }
        return $html;
    }
}
