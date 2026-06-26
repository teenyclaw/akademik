<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(protected StudentService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('students.index', compact('items'));
    }

    public function create(): View
    {
        return view('students.create', $this->formData(new Student));
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->service->create(
            $request->safe()->except(['photo', 'biodata']),
            $request->file('photo'),
            $request->input('biodata', [])
        );

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('students.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateStudentRequest $request, int $id): RedirectResponse
    {
        $this->service->update(
            $id,
            $request->safe()->except(['photo', 'biodata']),
            $request->file('photo'),
            $request->input('biodata', [])
        );

        return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }

    private function formData(Student $item): array
    {
        return [
            'item' => $item,
            'classes' => SchoolClass::query()->orderBy('name')->get(),
        ];
    }
}
