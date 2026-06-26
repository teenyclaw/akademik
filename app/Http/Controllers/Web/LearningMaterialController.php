<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LearningMaterial\StoreLearningMaterialRequest;
use App\Http\Requests\LearningMaterial\UpdateLearningMaterialRequest;
use App\Models\LearningMaterial;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Services\LearningMaterialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearningMaterialController extends Controller
{
    public function __construct(protected LearningMaterialService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('learning-materials.index', compact('items'));
    }

    public function create(): View
    {
        return view('learning-materials.create', $this->formData(new LearningMaterial));
    }

    public function store(StoreLearningMaterialRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('file'), $request->file('file'));

        return redirect()->route('learning-materials.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('learning-materials.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateLearningMaterialRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('file'), $request->file('file'));

        return redirect()->route('learning-materials.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('learning-materials.index')->with('success', 'Materi berhasil dihapus.');
    }

    private function formData(LearningMaterial $item): array
    {
        return [
            'item' => $item,
            'subjects' => Subject::query()->orderBy('name')->get(),
            'classes' => SchoolClass::query()->orderBy('name')->get(),
        ];
    }
}
