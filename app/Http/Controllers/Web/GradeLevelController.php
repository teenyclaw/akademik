<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeLevel\StoreGradeLevelRequest;
use App\Http\Requests\GradeLevel\UpdateGradeLevelRequest;
use App\Models\GradeLevel;
use App\Services\GradeLevelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeLevelController extends Controller
{
    public function __construct(protected GradeLevelService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('grade-levels.index', compact('items'));
    }

    public function create(): View
    {
        $item = new GradeLevel;

        return view('grade-levels.create', compact('item'));
    }

    public function store(StoreGradeLevelRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('grade-levels.index')->with('success', 'Tingkat Kelas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('grade-levels.edit', compact('item'));
    }

    public function update(UpdateGradeLevelRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('grade-levels.index')->with('success', 'Tingkat Kelas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('grade-levels.index')->with('success', 'Tingkat Kelas berhasil dihapus.');
    }

}
