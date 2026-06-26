<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\FinalGrade;
use App\Models\Grade;
use App\Models\GradeScale;
use App\Models\Student;
use App\Repositories\Contracts\FinalGradeRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FinalGradeService
{
    public function __construct(protected FinalGradeRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()
            ->with(['student', 'subject', 'semester'])
            ->paginate($perPage);
    }

    public function calculate(int $classId, int $semesterId, int $subjectId): int
    {
        $students = Student::query()->where('class_id', $classId)->pluck('id');
        $assessments = Assessment::query()
            ->where('class_id', $classId)
            ->where('semester_id', $semesterId)
            ->where('subject_id', $subjectId)
            ->with('component')
            ->get();

        $count = 0;

        DB::transaction(function () use ($students, $assessments, $semesterId, $subjectId, &$count) {
            foreach ($students as $studentId) {
                $totalWeight = 0;
                $weightedSum = 0;

                foreach ($assessments as $assessment) {
                    $grade = Grade::query()
                        ->where('student_id', $studentId)
                        ->where('assessment_id', $assessment->id)
                        ->value('score');

                    if ($grade !== null) {
                        $weight = (float) $assessment->component->weight;
                        $weightedSum += ($grade / $assessment->max_score) * 100 * $weight;
                        $totalWeight += $weight;
                    }
                }

                if ($totalWeight <= 0) {
                    continue;
                }

                $finalScore = round($weightedSum / $totalWeight, 2);
                $letter = $this->resolveLetter($finalScore);

                FinalGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'semester_id' => $semesterId,
                    ],
                    [
                        'score' => $finalScore,
                        'grade_letter' => $letter,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    private function resolveLetter(float $score): ?string
    {
        $scale = GradeScale::query()
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();

        return $scale?->grade_letter ?? ($score >= 75 ? 'B' : 'C');
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }
}
