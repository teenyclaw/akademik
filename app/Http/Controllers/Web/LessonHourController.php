<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonHour\StoreLessonHourRequest;
use App\Http\Requests\LessonHour\UpdateLessonHourRequest;
use App\Models\LessonHour;
use App\Services\LessonHourService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonHourController extends Controller
{
    public function __construct(protected LessonHourService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('lesson-hours.index', compact('items'));
    }

    public function create(): View
    {
        $item = new LessonHour;

        return view('lesson-hours.create', compact('item'));
    }

    public function store(StoreLessonHourRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('lesson-hours.index')->with('success', 'Jam Pelajaran berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('lesson-hours.edit', compact('item'));
    }

    public function update(UpdateLessonHourRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('lesson-hours.index')->with('success', 'Jam Pelajaran berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('lesson-hours.index')->with('success', 'Jam Pelajaran berhasil dihapus.');
    }

}
