<?php

namespace App\Enums;

enum RegistrationType: string
{
    use \App\Traits\EnumOptions;

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

}
