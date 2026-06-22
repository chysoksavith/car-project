<?php

namespace App\Enums;

enum ContainerStatus: string
{
    case OnTheWay = 'on_the_way';
    case InStock = 'in_stock';
    case Delivered = 'delivered';

    public function label(): string
    {
        return match($this) {
            self::OnTheWay => 'On The Way',
            self::InStock => 'In Stock',
            self::Delivered => 'Delivered',
        };
    }
}
