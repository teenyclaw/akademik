<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GradeResource;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GradeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $grades = Grade::query()
            ->with(['student', 'assessment'])
            ->when($request->student_id, fn ($q) => $q->where('student_id', $request->student_id))
            ->when($request->assessment_id, fn ($q) => $q->where('assessment_id', $request->assessment_id))
            ->paginate(15);

        return GradeResource::collection($grades);
    }

    public function show(int $id): GradeResource
    {
        $grade = Grade::query()->with(['student', 'assessment'])->findOrFail($id);

        return new GradeResource($grade);
    }
}
