<?php

namespace App\Traits;

trait EnumOptions
{
    // # Get all available options for dropdowns
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
