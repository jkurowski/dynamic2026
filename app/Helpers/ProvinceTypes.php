<?php

namespace App\Helpers;

class ProvinceTypes
{
    const DOLNOSLASKIE = 'dolnoslaskie';
    const KUJAWSKO_POMORSKIE = 'kujawsko-pomorskie';
    const LUBELSKIE = 'lubelskie';
    const LUBUSKIE = 'lubuskie';
    const LODZKIE = 'lodzkie';
    const MALOPOLSKIE = 'malopolskie';
    const MAZOWIECKIE = 'mazowieckie';
    const OPOLSKIE = 'opolskie';
    const PODKARPACKIE = 'podkarpackie';
    const PODLASKIE = 'podlaskie';
    const POMORSKIE = 'pomorskie';
    const SLASKIE = 'slaskie';
    const SWIETOKRZYSKIE = 'swietokrzyskie';
    const WARMIENSKO_MAZURSKIE = 'warminsko-mazurskie';
    const WIELKOPOLSKIE = 'wielkopolskie';
    const ZACHODNIOPOMORSKIE = 'zachodniopomorskie';

    public static function getProvinces(): array
    {
        return [
            self::DOLNOSLASKIE => 'Dolnośląskie',
            self::KUJAWSKO_POMORSKIE => 'Kujawsko-Pomorskie',
            self::LUBELSKIE => 'Lubelskie',
            self::LUBUSKIE => 'Lubuskie',
            self::LODZKIE => 'Łódzkie',
            self::MALOPOLSKIE => 'Małopolskie',
            self::MAZOWIECKIE => 'Mazowieckie',
            self::OPOLSKIE => 'Opolskie',
            self::PODKARPACKIE => 'Podkarpackie',
            self::PODLASKIE => 'Podlaskie',
            self::POMORSKIE => 'Pomorskie',
            self::SLASKIE => 'Śląskie',
            self::SWIETOKRZYSKIE => 'Świętokrzyskie',
            self::WARMIENSKO_MAZURSKIE => 'Warmińsko-Mazurskie',
            self::WIELKOPOLSKIE => 'Wielkopolskie',
            self::ZACHODNIOPOMORSKIE => 'Zachodniopomorskie',
        ];
    }

    public static function mapProvinceToCode(string $province): string
    {
        return match ($province) {
            self::DOLNOSLASKIE => 'DS',
            self::KUJAWSKO_POMORSKIE => 'KP',
            self::LUBELSKIE => 'LU',
            self::LUBUSKIE => 'LB',
            self::LODZKIE => 'LD',
            self::MALOPOLSKIE => 'MA',
            self::MAZOWIECKIE => 'MZ',
            self::OPOLSKIE => 'OP',
            self::PODKARPACKIE => 'PK',
            self::PODLASKIE => 'PL',
            self::POMORSKIE => 'PM',
            self::SLASKIE => 'SL',
            self::SWIETOKRZYSKIE => 'SK',
            self::WARMIENSKO_MAZURSKIE => 'WN',
            self::WIELKOPOLSKIE => 'WP',
            self::ZACHODNIOPOMORSKIE => 'ZP',
            default => 'XX',
        };
    }
}
