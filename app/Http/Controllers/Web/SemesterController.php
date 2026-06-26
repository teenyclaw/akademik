<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Semester\StoreSemesterRequest;
use App\Http\Requests\Semester\UpdateSemesterRequest;
use App\Models\Semester;
use App\Services\SemesterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function __construct(protected SemesterService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('semesters.index', compact('items'));
    }

    public function create(): View
    {
        $item = new Semester;
        $academicYears = \App\Models\AcademicYear::query()->orderByDesc('start_date')->get();

        return view('semesters.create', compact('item', 'academicYears'));
    }

    public function store(StoreSemesterRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('semesters.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);
        $academicYears = \App\Models\AcademicYear::query()->orderByDesc('start_date')->get();

        return view('semesters.edit', compact('item', 'academicYears'));
    }

    public function update(UpdateSemesterRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('semesters.index')->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('semesters.index')->with('success', 'Semester berhasil dihapus.');
    }

    public function activate(int $id): RedirectResponse
    {
        $this->service->activate($id);

        return redirect()->route('semesters.index')->with('success', 'Semester berhasil diaktifkan.');
    }
}
