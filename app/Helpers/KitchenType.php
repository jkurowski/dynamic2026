<?php

if (! function_exists('kitchenType')) {
    function kitchenType(int $number)
    {
        switch ($number) {
            case '1':
                return "Kuchnia";
            case '2':
                return "Aneks kuchenny";
        }
    }
}
