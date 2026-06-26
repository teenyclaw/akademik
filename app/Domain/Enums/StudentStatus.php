<?php

namespace App\Domain\Enums;

enum StudentStatus: string
{
    case Active = 'active';
    case Mutated = 'mutated';
    case Graduated = 'graduated';
    case Dropped = 'dropped';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Mutated => 'Mutasi',
            self::Graduated => 'Lulus',
            self::Dropped => 'Keluar',
            self::Inactive => 'Nonaktif',
        };
    }
}
