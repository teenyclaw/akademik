<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\SchoolClass;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $service) {}

    public function index(Request $request): View
    {
        $classes = SchoolClass::query()->orderBy('name')->get();
        $classId = $request->integer('class_id') ?: null;
        $date = $request->input('date', now()->toDateString());
        $records = $classId ? $this->service->getForClassAndDate($classId, $date) : collect();

        return view('attendances.index', compact('classes', 'classId', 'date', 'records'));
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $this->service->storeBulk(
            $request->integer('class_id'),
            $request->input('date'),
            $request->input('attendances', [])
        );

        return redirect()
            ->route('attendances.index', ['class_id' => $request->class_id, 'date' => $request->date])
            ->with('success', 'Absensi berhasil disimpan.');
    }
}
