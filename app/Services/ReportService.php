<?php

namespace App\Services;

use App\Exports\AttendanceExport;
use App\Exports\GradesExport;
use App\Exports\PaymentsExport;
use App\Exports\StudentsExport;
use App\Exports\TeachersExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportService
{
    public function exportStudents(): BinaryFileResponse
    {
        return Excel::download(new StudentsExport, 'siswa-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportTeachers(): BinaryFileResponse
    {
        return Excel::download(new TeachersExport, 'guru-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportAttendance(?int $classId, ?string $date): BinaryFileResponse
    {
        return Excel::download(new AttendanceExport($classId, $date), 'absensi-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportGrades(?int $semesterId, ?int $classId): BinaryFileResponse
    {
        return Excel::download(new GradesExport($semesterId, $classId), 'nilai-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPayments(): BinaryFileResponse
    {
        return Excel::download(new PaymentsExport, 'pembayaran-'.now()->format('Y-m-d').'.xlsx');
    }
}
