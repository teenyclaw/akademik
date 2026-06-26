<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Teacher::query()->get()->map(fn ($t) => [
            $t->nip,
            $t->name,
            $t->gender,
            $t->phone,
            $t->specialization,
            $t->status,
        ]);
    }

    public function headings(): array
    {
        return ['NIP', 'Nama', 'Jenis Kelamin', 'Telepon', 'Spesialisasi', 'Status'];
    }
}
