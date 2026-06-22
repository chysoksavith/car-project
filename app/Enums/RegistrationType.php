<?php

namespace App\Enums;

enum RegistrationType: string
{
    case TAX_PAPER = 'TAX_PAPER';
    case PLATE_NUMBER = 'PLATE_NUMBER';

    // # Get human-readable label
    public function label(): string
    {
        return match($this) {
            self::TAX_PAPER => 'Tax Paper',
            self::PLATE_NUMBER => 'Plate Number',
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
