<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;
use App\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('subjects.index', compact('items'));
    }

    public function create(): View
    {
        $item = new Subject;

        return view('subjects.create', compact('item'));
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('subjects.edit', compact('item'));
    }

    public function update(UpdateSubjectRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('subjects.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('subjects.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

}
