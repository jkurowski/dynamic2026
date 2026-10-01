<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailArray implements Rule
{
    public function passes($attribute, $value)
    {
        $decodedValue = json_decode($value, true);

        if (!is_array($decodedValue)) {
            return false;
        }

        foreach ($decodedValue as $item) {
            if (!isset($item['value']) || !filter_var($item['value'], FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'Jeden z podanych adresów e-mail ma zły format';
    }
}