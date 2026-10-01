<?php

namespace App\Helpers;

class RoomStatusMaper
{
    const FREE = 1;
    const RESERVED = 2;
    const SOLD = 3;
    const RENTED = 4;
    const DEVELOPERS_AGREEMENT = 5;
    const PRE_SALE_AGREEMENT = 6;

    public static function getStatusName(int $status)
    {
        switch ($status) {
            case self::FREE:
                return 'Dostępne';
            case self::RESERVED:
                return 'Rezerwacja';
            case self::SOLD:
                return 'Sprzedane';
            case self::RENTED:
                return 'Wynajęte';
            case self::DEVELOPERS_AGREEMENT:
                return 'Umowa deweloperska';
            case self::PRE_SALE_AGREEMENT:
                return 'Umowa przedsprzedażowa';
            default:
                return 'Nieznany status';
        }
    }
    public static function getAll()
    {
        return [
            self::FREE => self::getStatusName(self::FREE),
            self::RESERVED => self::getStatusName(self::RESERVED),
            self::SOLD => self::getStatusName(self::SOLD),
            self::RENTED => self::getStatusName(self::RENTED),
            self::DEVELOPERS_AGREEMENT => self::getStatusName(self::DEVELOPERS_AGREEMENT),
            self::PRE_SALE_AGREEMENT => self::getStatusName(self::PRE_SALE_AGREEMENT)
        ];
    }

    public static function getForClient()
    {
        return [
            self::FREE => self::getStatusName(self::FREE),
            self::RESERVED => self::getStatusName(self::RESERVED),
            self::SOLD => self::getStatusName(self::SOLD),
            self::RENTED => self::getStatusName(self::RENTED),
            self::DEVELOPERS_AGREEMENT => self::getStatusName(self::DEVELOPERS_AGREEMENT),
            self::PRE_SALE_AGREEMENT => self::getStatusName(self::PRE_SALE_AGREEMENT)
        ];
    }
}
