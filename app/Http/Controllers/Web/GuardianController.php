<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guardian\StoreGuardianRequest;
use App\Http\Requests\Guardian\UpdateGuardianRequest;
use App\Models\Guardian;
use App\Models\Student;
use App\Services\GuardianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function __construct(protected GuardianService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('guardians.index', compact('items'));
    }

    public function create(): View
    {
        return view('guardians.create', $this->formData(new Guardian));
    }

    public function store(StoreGuardianRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('student_ids'), $request->input('student_ids', []));

        return redirect()->route('guardians.index')->with('success', 'Orang tua berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('guardians.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateGuardianRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('student_ids'), $request->input('student_ids', []));

        return redirect()->route('guardians.index')->with('success', 'Orang tua berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('guardians.index')->with('success', 'Orang tua berhasil dihapus.');
    }

    private function formData(Guardian $item): array
    {
        return [
            'item' => $item,
            'students' => Student::query()->orderBy('name')->get(),
        ];
    }
}
