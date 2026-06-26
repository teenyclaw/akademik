<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Student::query()->with('schoolClass')->get()->map(fn ($s) => [
            $s->nis,
            $s->nisn,
            $s->name,
            $s->gender,
            $s->schoolClass?->name,
            $s->status?->value ?? $s->status,
        ]);
    }

    public function headings(): array
    {
        return ['NIS', 'NISN', 'Nama', 'Jenis Kelamin', 'Kelas', 'Status'];
    }
}
