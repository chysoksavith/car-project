<?php

namespace App\Enums;

enum InventoryStatus: string
{
    use \App\Traits\EnumOptions;

    case ON_THE_WAY = 'on_the_way';
    case IN_STOCK = 'in_stock';

    // # Get human-readable label
    public function label(): string
    {
        return match($this) {
            self::ON_THE_WAY => 'On The Way',
            self::IN_STOCK => 'In Stock',
        };
    }

}
