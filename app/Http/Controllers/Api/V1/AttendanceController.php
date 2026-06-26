<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttendanceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $attendances = Attendance::query()
            ->with(['student'])
            ->when($request->student_id, fn ($q) => $q->where('student_id', $request->student_id))
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->date, fn ($q) => $q->whereDate('date', $request->date))
            ->paginate(15);

        return AttendanceResource::collection($attendances);
    }

    public function show(int $id): AttendanceResource
    {
        $attendance = Attendance::query()->with(['student'])->findOrFail($id);

        return new AttendanceResource($attendance);
    }
}
