<?php

namespace App\Services\Leads;

use App\Interfaces\LeadEmailProcessingStrategy;


class ObidoStrategy implements LeadEmailProcessingStrategy
{

    private $portal_name = 'obido.pl';
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
        $pattern = '/klienta\s*<mailto:(.*)>/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerPhone($message)
    {
        $pattern = '/Numer telefonu:\s*.*(tel:(.*)>)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[2]);
        } else {
            return null;
        }
    }

    public function findInvestmentName($message)
    {
        $pattern = '/Inwestycja:\s*([^\n]+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findPropertyName($message)
    {
        $pattern = '/mieszkanie:\s*([^\n]+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }
}
