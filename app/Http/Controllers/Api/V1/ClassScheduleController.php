<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassScheduleResource;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClassScheduleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $schedules = ClassSchedule::query()
            ->with(['schoolClass', 'subject', 'teacher', 'room', 'lessonHour'])
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->semester_id, fn ($q) => $q->where('semester_id', $request->semester_id))
            ->paginate(15);

        return ClassScheduleResource::collection($schedules);
    }

    public function show(int $id): ClassScheduleResource
    {
        $schedule = ClassSchedule::query()
            ->with(['schoolClass', 'subject', 'teacher', 'room', 'lessonHour'])
            ->findOrFail($id);

        return new ClassScheduleResource($schedule);
    }
}
