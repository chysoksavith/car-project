<?php

namespace App\Enums;

enum ContainerStatus: string
{
    use \App\Traits\EnumOptions;

    case OnTheWay = 'on_the_way';
    case InStock = 'in_stock';

    public function label(): string
    {
        return match($this) {
            self::OnTheWay => 'On The Way',
            self::InStock => 'In Stock',
        };
    }
}
