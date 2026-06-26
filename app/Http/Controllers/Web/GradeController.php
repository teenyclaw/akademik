<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Grade\StoreGradeRequest;
use App\Models\Assessment;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct(protected GradeService $service) {}

    public function index(Request $request): View
    {
        $assessments = Assessment::query()->with(['subject', 'schoolClass'])->orderByDesc('date')->paginate(15);
        $assessmentId = $request->integer('assessment_id') ?: null;
        $records = $assessmentId ? $this->service->getForAssessment($assessmentId) : collect();

        return view('grades.index', compact('assessments', 'assessmentId', 'records'));
    }

    public function store(StoreGradeRequest $request): RedirectResponse
    {
        $this->service->storeBulk($request->integer('assessment_id'), $request->input('scores', []));

        return redirect()
            ->route('grades.index', ['assessment_id' => $request->assessment_id])
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
