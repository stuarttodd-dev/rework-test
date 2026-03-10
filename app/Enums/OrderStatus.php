<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Placed = 'placed';
    case Dispatched = 'dispatched';
    case Cancelled = 'cancelled';

    public static function getDefault(): self
    {
        return self::Placed;
    }
}
