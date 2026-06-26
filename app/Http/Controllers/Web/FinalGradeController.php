<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalGrade\CalculateFinalGradeRequest;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Services\FinalGradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FinalGradeController extends Controller
{
    public function __construct(protected FinalGradeService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);
        $classes = SchoolClass::query()->orderBy('name')->get();
        $semesters = Semester::query()->orderByDesc('start_date')->get();
        $subjects = Subject::query()->orderBy('name')->get();

        return view('final-grades.index', compact('items', 'classes', 'semesters', 'subjects'));
    }

    public function calculate(CalculateFinalGradeRequest $request): RedirectResponse
    {
        $count = $this->service->calculate(
            $request->integer('class_id'),
            $request->integer('semester_id'),
            $request->integer('subject_id')
        );

        return redirect()->route('final-grades.index')->with('success', "Nilai akhir {$count} siswa berhasil dihitung.");
    }
}
