<?php

namespace App\Enums;

enum StockMovementType: string
{
    case STOCK_IN = 'stock_in';
    case STOCK_OUT = 'stock_out';
    case RESERVATION = 'reservation';
    case RESERVATION_RELEASE = 'reservation_release';

    public function affectsAvailableNegatively(): bool
    {
        return in_array($this, [
            self::STOCK_OUT,
            self::RESERVATION,
        ], true);
    }
}