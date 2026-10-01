<?php

namespace App\Services\Leads;

use App\Interfaces\LeadEmailProcessingStrategy;


class DominiumStrategy implements LeadEmailProcessingStrategy
{

    private $portal_name = 'dominium.pl';
    public function process($message)
    {   
        

        $data = [
            'portal_name' => $this->portal_name,
            'name' => $this->findCustomerName($message),
            'email' => $this->findCustomerEmail($message),
            'phone' => $this->findCustomerPhone($message),
            'investment_name' => $this->findInvestmentName($message),
            'property_name' => $this->findPropertyName($message),
            'message' => $message
        ];

        // remove CRLF from strings if any
        foreach ($data as $key => $value) {
            if($key !=='message' && $value !== null) {
                $data[$key] = str_replace(["\r", "\n"], '', $value);
            }
        }

        return $data;
    }

    public function findCustomerName($message)
    {
        $pattern = '/Imię i nazwisko:\s*([^\(]+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerEmail($message)
    {
        $pattern = '/(?:Adres e-mail:|E-mail:)\s*([^\(]+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerPhone($message)
    {
        $pattern = '/Telefon kontaktowy:\s*(\d+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findInvestmentName($message)
    {
        $pattern = '/(w inwestycji|inwestycją)\s(.*?)(\r?\n|$)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[2], " \n\r\t\v\x00.");
        } else {
            return null;
        }
    }

    public function findPropertyName($message)
    {
        $pattern = '/numer:\s*([\w\.]+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }
}
