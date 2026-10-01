<?php

if (! function_exists('json2array')) {
    function json2array($jsondata)
    {
        return $jsondata[0];
    }
}
