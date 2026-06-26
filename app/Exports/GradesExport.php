<?php

namespace App\Exports;

use App\Models\FinalGrade;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GradesExport implements FromCollection, WithHeadings
{
    public function __construct(protected ?int $semesterId = null, protected ?int $classId = null) {}

    public function collection(): Collection
    {
        return FinalGrade::query()
            ->with(['student.schoolClass', 'subject', 'semester'])
            ->when($this->semesterId, fn ($q) => $q->where('semester_id', $this->semesterId))
            ->when($this->classId, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('class_id', $this->classId)))
            ->get()
            ->map(fn ($g) => [
                $g->student?->nis,
                $g->student?->name,
                $g->student?->schoolClass?->name,
                $g->subject?->name,
                $g->semester?->name,
                $g->score,
                $g->grade_letter,
            ]);
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Kelas', 'Mapel', 'Semester', 'Nilai', 'Huruf'];
    }
}
