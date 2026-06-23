<?php

namespace App\Enums;

enum SalesStatus: string
{
    use \App\Traits\EnumOptions;

    case AVAILABLE = 'AVAILABLE';
    case BOOKED = 'BOOKED';
    case RESERVED = 'RESERVED';
    case SOLD = 'SOLD';

    // # Get human-readable label
    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Available',
            self::BOOKED => 'Booked',
            self::RESERVED => 'Reserved',
            self::SOLD => 'Sold',
        };
    }

}
