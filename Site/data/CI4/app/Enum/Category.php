<?php

namespace App\Enum;

enum Category: string
{
    case PANTALON = 'pantalons';
    case TSHIRT = 'tshirts';
    case PULL = 'pulls';
    case ACCESSOIRE = 'accessoire';

    public function label(): string
    {
        return match ($this) {
            self::PANTALON => 'Pantalon',
            self::TSHIRT => 'tshirts',
            self::PULL => 'Pull',
            self::ACCESSOIRE => 'Accessoire',
        };
    }
}
