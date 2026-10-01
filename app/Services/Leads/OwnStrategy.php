<?php

namespace App\Services\Leads;

use App\Interfaces\LeadEmailProcessingStrategy;

// Template
// INWESTYCJA: Testowa
// MIESZKANIE: 2
// IMIE: imie i naziwsko
// EMAIL: email
// TELEFON: telefon
// OPIS:
// Wiadomość

// ZGODY:
// ZGODA_MARKETING: 1 || 0
// ZGODA_MAIL: 1 || 0
// ZGODA_TEL: 1 || 0

class OwnStrategy implements LeadEmailProcessingStrategy
{
    private $portal_name = 'wlasny';

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
        $pattern = '/Imie:\s*([^\n]+)/i';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerEmail($message)
    {
        $pattern = '/email:\s*([^\n]+)/i';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerPhone($message)
    {
        $pattern = '/telefon:\s*([^\n]+)/i';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findInvestmentName($message)
    {
        $pattern = '/INWESTYCJA:\s*([^\n]+)/i';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findPropertyName($message)
    {
        $pattern = '/MIESZKANIE:\s*([^\n]+)/i';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }
}
