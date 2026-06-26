<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYear\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYear\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use App\Services\AcademicYearService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function __construct(protected AcademicYearService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('academic-years.index', compact('items'));
    }

    public function create(): View
    {
        $item = new AcademicYear;

        return view('academic-years.create', compact('item'));
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('academic-years.edit', compact('item'));
    }

    public function update(UpdateAcademicYearRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    public function activate(int $id): RedirectResponse
    {
        $this->service->activate($id);

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil diaktifkan.');
    }
}
