<?php

namespace App\Enums;

enum Transmission: string
{
    case AUTOMATIC = 'AUTOMATIC';
    case MANUAL = 'MANUAL';
    case CVT = 'CVT';

    // # Get human-readable label
    public function label(): string
    {
        return match($this) {
            self::AUTOMATIC => 'Automatic',
            self::MANUAL => 'Manual',
            self::CVT => 'CVT',
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
