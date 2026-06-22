<?php

namespace App\Enums;

enum SalesStatus: string
{
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

    // # Get all available options for dropdowns
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
