<?php

namespace App\Helpers;

class TemplateTypes
{
    const EMPTY = '0';
    const EMAIL = '10';
    const OFFER = '20';

    public static function getTypes(): array
    {
        return [
            self::EMPTY => 'Brak',
            self::EMAIL => 'Email',
            self::OFFER => 'Oferta',
        ];
    }

    public static function mapTypeToLayout(string $type): string
    {
        return match ($type) {
            self::EMPTY => 'empty',
            self::EMAIL => 'email',
            self::OFFER => 'offer',
            default => 'empty',
        };
    }
   
}
