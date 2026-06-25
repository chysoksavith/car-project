<?php

namespace App\Enums;

enum UserType: string
{
    use \App\Traits\EnumOptions;

    case FRONTEND = 'frontend';
    case BACKEND = 'backend';

    public function label(): string
    {
        return match($this) {
            self::FRONTEND => 'Frontend',
            self::BACKEND => 'Backend',
        };
    }
}
