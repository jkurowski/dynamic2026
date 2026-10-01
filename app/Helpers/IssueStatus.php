<?php

if (! function_exists('issueStatus')) {
    function issueStatus(int $number)
    {
        switch ($number) {
            case '1':
                return "Nowe";
            case '2':
                return "W toku";
            case '3':
                return "Zakończone";
            case '4':
                return "Odrzucone";
            case '5':
                return "Zawieszone";
        }
    }
}