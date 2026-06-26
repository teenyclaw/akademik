<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\EducationLevel;
use App\Models\School;
use App\Services\SchoolService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function __construct(protected SchoolService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('schools.index', compact('items'));
    }

    public function create(): View
    {
        $item = new School;
        $educationLevels = EducationLevel::query()->orderBy('name')->get();

        return view('schools.create', compact('item', 'educationLevels'));
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('logo'), $request->file('logo'));

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);
        $educationLevels = EducationLevel::query()->orderBy('name')->get();

        return view('schools.edit', compact('item', 'educationLevels'));
    }

    public function update(UpdateSchoolRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('logo'), $request->file('logo'));

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil dihapus.');
    }
}