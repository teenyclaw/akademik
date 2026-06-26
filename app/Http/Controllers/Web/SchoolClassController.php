<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolClass\StoreSchoolClassRequest;
use App\Http\Requests\SchoolClass\UpdateSchoolClassRequest;
use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Services\SchoolClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function __construct(protected SchoolClassService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('classes.index', compact('items'));
    }

    public function create(): View
    {
        return view('classes.create', $this->formData(new SchoolClass));
    }

    public function store(StoreSchoolClassRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('classes.edit', $this->formData($item));
    }

    public function update(UpdateSchoolClassRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
    }

    private function formData(SchoolClass $item): array
    {
        return [
            'item' => $item,
            'academicYears' => AcademicYear::query()->orderByDesc('start_date')->get(),
            'gradeLevels' => GradeLevel::query()->orderBy('level_number')->get(),
            'majors' => Major::query()->orderBy('name')->get(),
            'teachers' => Teacher::query()->orderBy('name')->get(),
        ];
    }
}