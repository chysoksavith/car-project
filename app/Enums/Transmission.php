<?php

namespace App\Enums;

enum Transmission: string
{
    use \App\Traits\EnumOptions;

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

}
