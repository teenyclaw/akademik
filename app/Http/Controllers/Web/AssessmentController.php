<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreAssessmentRequest;
use App\Http\Requests\Assessment\UpdateAssessmentRequest;
use App\Models\Assessment;
use App\Models\AssessmentComponent;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Services\AssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function __construct(protected AssessmentService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('assessments.index', compact('items'));
    }

    public function create(): View
    {
        return view('assessments.create', $this->formData(new Assessment));
    }

    public function store(StoreAssessmentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('assessments.index')->with('success', 'Penilaian berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('assessments.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateAssessmentRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('assessments.index')->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('assessments.index')->with('success', 'Penilaian berhasil dihapus.');
    }

    private function formData(Assessment $item): array
    {
        return [
            'item' => $item,
            'semesters' => Semester::query()->orderByDesc('start_date')->get(),
            'subjects' => Subject::query()->orderBy('name')->get(),
            'classes' => SchoolClass::query()->orderBy('name')->get(),
            'components' => AssessmentComponent::query()->orderBy('sort_order')->get(),
        ];
    }
}
