<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $students = Student::query()
            ->with(['schoolClass', 'biodata'])
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->paginate(15);

        return StudentResource::collection($students);
    }

    public function show(int $id): StudentResource
    {
        $student = Student::query()->with(['schoolClass', 'biodata'])->findOrFail($id);

        return new StudentResource($student);
    }
}
