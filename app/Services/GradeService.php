<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Student;
use App\Repositories\Contracts\GradeRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GradeService
{
    public function __construct(protected GradeRepositoryInterface $repository) {}

    public function getForAssessment(int $assessmentId): Collection
    {
        $assessment = app(AssessmentService::class)->findOrFail($assessmentId);
        $students = Student::query()->where('class_id', $assessment->class_id)->orderBy('name')->get();
        $existing = Grade::query()->where('assessment_id', $assessmentId)->get()->keyBy('student_id');

        return $students->map(fn ($student) => [
            'student' => $student,
            'grade' => $existing->get($student->id),
        ]);
    }

    public function storeBulk(int $assessmentId, array $scores): void
    {
        DB::transaction(function () use ($assessmentId, $scores) {
            foreach ($scores as $studentId => $score) {
                Grade::updateOrCreate(
                    ['student_id' => $studentId, 'assessment_id' => $assessmentId],
                    ['score' => $score !== '' ? $score : null]
                );
            }
        });
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->getModel()->newQuery()->with(['student', 'assessment'])->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }
}
