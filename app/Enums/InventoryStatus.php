<?php

namespace App\Enums;

enum InventoryStatus: string
{
    case IN_TRANSIT = 'IN_TRANSIT';
    case IN_SHOWROOM = 'IN_SHOWROOM';
    case DELIVERED = 'DELIVERED';

    // # Get human-readable label
    public function label(): string
    {
        return match($this) {
            self::IN_TRANSIT => 'In Transit',
            self::IN_SHOWROOM => 'In Showroom',
            self::DELIVERED => 'Delivered',
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
