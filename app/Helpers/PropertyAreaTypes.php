<?php

namespace App\Helpers;

class PropertyAreaTypes {
    const ROOM_APARTMENT = 1;
    const STORAGE = 2;
    const PARKING = 3;
    const GARDEN = 4;
    const SHOP = 5;
    const PARKING_STORAGE = 6;

    private const STATUS_MAP = [
        self::ROOM_APARTMENT => 'Mieszkanie / Apartament',
        self::STORAGE => 'Komórka lokatorska',
        self::PARKING => 'Miejsce parkingowe',
        self::GARDEN => 'Ogródek',
        self::SHOP => 'Lokal usługowy',
        self::PARKING_STORAGE => 'Miejsce parkingowe z komórką lokatorską',
    ];

    public static function getStatusText(int $statusValue): ?string
    {
        return self::STATUS_MAP[$statusValue] ?? null;
    }

    public static function getAll(): array
    {
        return self::STATUS_MAP;
    }

    public static function getOthers(): array
    {
        return array_intersect_key(self::STATUS_MAP, [
            self::STORAGE => true,
            self::PARKING => true,
            self::GARDEN => true,
            self::SHOP => true,
            self::PARKING_STORAGE => true,
        ]);
    }
}
