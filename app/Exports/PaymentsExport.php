<?php

namespace App\Exports;

use App\Models\PaymentBill;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return PaymentBill::query()->with(['student', 'feeType'])->get()->map(fn ($b) => [
            $b->student?->nis,
            $b->student?->name,
            $b->feeType?->name,
            $b->amount,
            $b->due_date?->format('Y-m-d'),
            $b->status?->value ?? $b->status,
            $b->period,
        ]);
    }

    public function headings(): array
    {
        return ['NIS', 'Nama Siswa', 'Jenis Biaya', 'Nominal', 'Jatuh Tempo', 'Status', 'Periode'];
    }
}
