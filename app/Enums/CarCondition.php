<?php

namespace App\Enums;

enum CarCondition: string
{
    case NEW = 'NEW';
    case USED = 'USED';

    // # Get human-readable label
    public function label(): string
    {
        return match($this) {
            self::NEW => 'New',
            self::USED => 'Used',
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
