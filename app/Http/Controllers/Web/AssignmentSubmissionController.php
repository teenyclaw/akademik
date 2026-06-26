<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentSubmission\StoreAssignmentSubmissionRequest;
use App\Http\Requests\AssignmentSubmission\UpdateAssignmentSubmissionRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Services\AssignmentSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssignmentSubmissionController extends Controller
{
    public function __construct(protected AssignmentSubmissionService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('assignment-submissions.index', compact('items'));
    }

    public function create(): View
    {
        return view('assignment-submissions.create', $this->formData(new AssignmentSubmission));
    }

    public function store(StoreAssignmentSubmissionRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('file'), $request->file('file'));

        return redirect()->route('assignment-submissions.index')->with('success', 'Pengumpulan tugas berhasil disimpan.');
    }

    public function edit(int $id): View
    {
        return view('assignment-submissions.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateAssignmentSubmissionRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('file'), $request->file('file'));

        return redirect()->route('assignment-submissions.index')->with('success', 'Pengumpulan tugas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('assignment-submissions.index')->with('success', 'Pengumpulan tugas berhasil dihapus.');
    }

    private function formData(AssignmentSubmission $item): array
    {
        return [
            'item' => $item,
            'assignments' => Assignment::query()->orderByDesc('deadline')->get(),
            'students' => Student::query()->orderBy('name')->get(),
        ];
    }
}
