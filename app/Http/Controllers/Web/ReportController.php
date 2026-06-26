<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $service) {}

    public function index(): View
    {
        $classes = SchoolClass::query()->orderBy('name')->get();
        $semesters = Semester::query()->orderByDesc('start_date')->get();

        return view('reports.index', compact('classes', 'semesters'));
    }

    public function exportStudents(): BinaryFileResponse
    {
        return $this->service->exportStudents();
    }

    public function exportTeachers(): BinaryFileResponse
    {
        return $this->service->exportTeachers();
    }

    public function exportAttendance(Request $request): BinaryFileResponse
    {
        return $this->service->exportAttendance(
            $request->integer('class_id') ?: null,
            $request->input('date')
        );
    }

    public function exportGrades(Request $request): BinaryFileResponse
    {
        return $this->service->exportGrades(
            $request->integer('semester_id') ?: null,
            $request->integer('class_id') ?: null
        );
    }

    public function exportPayments(): BinaryFileResponse
    {
        return $this->service->exportPayments();
    }
}
