<?php

namespace App\Services\Leads;

use App\Interfaces\LeadEmailProcessingStrategy;

class RynekPierwotnyStrategy implements LeadEmailProcessingStrategy
{

    private $portal_name = 'rynekpierwotny.pl';

    public function process($message)
    {
        $data = [
            'portal_name' => $this->portal_name,
            'name' => $this->findCustomerName($message),
            'email' => null,
            'phone' => null,
            'investment_name' => $this->findInvestmentName($message),
            'property_name' => $this->findPropertyName($message),
            'message' => $message
        ];

        // remove CRLF from strings if any
        foreach ($data as $key => $value) {
            if ($key !== 'message' && $value !== null) {
                $data[$key] = str_replace(["\r", "\n"], '', $value);
            }
        }


        return  $data;
    }

    public function findCustomerName($message)
    {
        $pattern = '/Klienta\s+(.+?)\s+interesuje/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findInvestmentName($message)
    {
        $pattern = '/w inwestycji\s+(.+?)\s+\./';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1], ' *');
        } else {
            return null;
        }
    }

    public function findPropertyName($message)
    {
        $pattern = '/nr\s(.*)\sw/i';
        if (preg_match($pattern, $message, $matches)) {
            return $matches[1];
        } else {
            return null;
        }
    }
}
