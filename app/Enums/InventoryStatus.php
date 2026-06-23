<?php

namespace App\Enums;

enum InventoryStatus: string
{
    use \App\Traits\EnumOptions;

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

}
