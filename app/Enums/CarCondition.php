<?php

namespace App\Enums;

enum CarCondition: string
{
    use \App\Traits\EnumOptions;

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

}
