<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(protected TeacherService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('teachers.index', compact('items'));
    }

    public function create(): View
    {
        $item = new Teacher;

        return view('teachers.create', compact('item'));
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('photo'), $request->file('photo'));

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('teachers.edit', compact('item'));
    }

    public function update(UpdateTeacherRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('photo'), $request->file('photo'));

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}
