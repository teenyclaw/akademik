<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentComponent\StoreAssessmentComponentRequest;
use App\Http\Requests\AssessmentComponent\UpdateAssessmentComponentRequest;
use App\Models\AssessmentComponent;
use App\Services\AssessmentComponentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssessmentComponentController extends Controller
{
    public function __construct(protected AssessmentComponentService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('assessment-components.index', compact('items'));
    }

    public function create(): View
    {
        $item = new AssessmentComponent;

        return view('assessment-components.create', compact('item'));
    }

    public function store(StoreAssessmentComponentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('assessment-components.index')->with('success', 'Komponen Penilaian berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('assessment-components.edit', compact('item'));
    }

    public function update(UpdateAssessmentComponentRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('assessment-components.index')->with('success', 'Komponen Penilaian berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('assessment-components.index')->with('success', 'Komponen Penilaian berhasil dihapus.');
    }

}
