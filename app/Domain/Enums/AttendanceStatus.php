<?php

namespace App\Domain\Enums;

enum AttendanceStatus: string
{
    case Present = 'H';
    case Sick = 'S';
    case Permission = 'I';
    case Absent = 'A';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Hadir',
            self::Sick => 'Sakit',
            self::Permission => 'Izin',
            self::Absent => 'Alpha',
        };
    }
}
