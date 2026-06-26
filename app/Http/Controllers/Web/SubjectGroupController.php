<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectGroup\StoreSubjectGroupRequest;
use App\Http\Requests\SubjectGroup\UpdateSubjectGroupRequest;
use App\Models\SubjectGroup;
use App\Services\SubjectGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectGroupController extends Controller
{
    public function __construct(protected SubjectGroupService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('subject-groups.index', compact('items'));
    }

    public function create(): View
    {
        $item = new SubjectGroup;

        return view('subject-groups.create', compact('item'));
    }

    public function store(StoreSubjectGroupRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('subject-groups.index')->with('success', 'Kelompok Mapel berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('subject-groups.edit', compact('item'));
    }

    public function update(UpdateSubjectGroupRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('subject-groups.index')->with('success', 'Kelompok Mapel berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('subject-groups.index')->with('success', 'Kelompok Mapel berhasil dihapus.');
    }

}
