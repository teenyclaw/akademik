<?php

namespace App\Domain\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Partial = 'partial';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Belum Bayar',
            self::Partial => 'Sebagian',
            self::Paid => 'Lunas',
        };
    }
}
