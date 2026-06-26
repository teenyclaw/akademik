<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportCard\GenerateReportCardRequest;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Services\ReportCardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function __construct(protected ReportCardService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);
        $classes = SchoolClass::query()->orderBy('name')->get();
        $semesters = Semester::query()->orderByDesc('start_date')->get();
        $students = Student::query()->orderBy('name')->get();

        return view('report-cards.index', compact('items', 'classes', 'semesters', 'students'));
    }

    public function generate(GenerateReportCardRequest $request): RedirectResponse
    {
        if ($request->filled('student_id')) {
            $this->service->generate(
                $request->integer('student_id'),
                $request->integer('semester_id'),
                $request->integer('class_id')
            );
            $message = 'Rapor berhasil digenerate.';
        } else {
            $count = $this->service->generateForClass(
                $request->integer('class_id'),
                $request->integer('semester_id')
            );
            $message = "Rapor {$count} siswa berhasil digenerate.";
        }

        return redirect()->route('report-cards.index')->with('success', $message);
    }

    public function show(int $id): View
    {
        $reportCard = $this->service->findOrFail($id);

        return view('report-cards.show', compact('reportCard'));
    }

    public function pdf(int $id): Response
    {
        return $this->service->exportPdf($id);
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('report-cards.index')->with('success', 'Rapor berhasil dihapus.');
    }
}
