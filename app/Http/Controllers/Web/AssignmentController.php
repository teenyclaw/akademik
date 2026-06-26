<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\StoreAssignmentRequest;
use App\Http\Requests\Assignment\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Services\AssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function __construct(protected AssignmentService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('assignments.index', compact('items'));
    }

    public function create(): View
    {
        return view('assignments.create', $this->formData(new Assignment));
    }

    public function store(StoreAssignmentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('assignments.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateAssignmentRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }

    private function formData(Assignment $item): array
    {
        return [
            'item' => $item,
            'subjects' => Subject::query()->orderBy('name')->get(),
            'classes' => SchoolClass::query()->orderBy('name')->get(),
        ];
    }
}
