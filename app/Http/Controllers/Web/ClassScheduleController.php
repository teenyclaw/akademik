<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassSchedule\StoreClassScheduleRequest;
use App\Http\Requests\ClassSchedule\UpdateClassScheduleRequest;
use App\Models\ClassSchedule;
use App\Models\LessonHour;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ClassScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassScheduleController extends Controller
{
    public function __construct(protected ClassScheduleService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('class-schedules.index', compact('items'));
    }

    public function create(): View
    {
        return view('class-schedules.create', $this->formData(new ClassSchedule));
    }

    public function store(StoreClassScheduleRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('class-schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('class-schedules.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateClassScheduleRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('class-schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('class-schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    private function formData(ClassSchedule $item): array
    {
        return [
            'item' => $item,
            'classes' => SchoolClass::query()->orderBy('name')->get(),
            'subjects' => Subject::query()->orderBy('name')->get(),
            'teachers' => Teacher::query()->orderBy('name')->get(),
            'rooms' => Room::query()->orderBy('name')->get(),
            'lessonHours' => LessonHour::query()->orderBy('sort_order')->get(),
            'semesters' => Semester::query()->orderByDesc('start_date')->get(),
            'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
        ];
    }
}
