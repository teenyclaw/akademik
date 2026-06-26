<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    public function __construct(protected ?int $classId = null, protected ?string $date = null) {}

    public function collection(): Collection
    {
        return Attendance::query()
            ->with(['student', 'schoolClass'])
            ->when($this->classId, fn ($q) => $q->where('class_id', $this->classId))
            ->when($this->date, fn ($q) => $q->whereDate('date', $this->date))
            ->get()
            ->map(fn ($a) => [
                $a->date?->format('Y-m-d'),
                $a->schoolClass?->name,
                $a->student?->nis,
                $a->student?->name,
                $a->status,
                $a->notes,
            ]);
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kelas', 'NIS', 'Nama Siswa', 'Status', 'Catatan'];
    }
}
