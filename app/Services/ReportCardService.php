<?php

namespace App\Services;

use App\Models\FinalGrade;
use App\Models\ReportCard;
use App\Models\ReportCardDetail;
use App\Models\Student;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportCardService
{
    public function __construct(protected ReportCardRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()
            ->with(['student', 'semester', 'schoolClass'])
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->getModel()->newQuery()
            ->with(['student.biodata', 'semester', 'schoolClass', 'details.subject'])
            ->findOrFail($id);
    }

    public function generate(int $studentId, int $semesterId, int $classId): Model
    {
        return DB::transaction(function () use ($studentId, $semesterId, $classId) {
            $reportCard = ReportCard::updateOrCreate(
                ['student_id' => $studentId, 'semester_id' => $semesterId],
                [
                    'class_id' => $classId,
                    'status' => 'draft',
                    'generated_at' => now(),
                ]
            );

            $finalGrades = FinalGrade::query()
                ->where('student_id', $studentId)
                ->where('semester_id', $semesterId)
                ->get();

            foreach ($finalGrades as $fg) {
                ReportCardDetail::updateOrCreate(
                    ['report_card_id' => $reportCard->id, 'subject_id' => $fg->subject_id],
                    [
                        'score' => $fg->score,
                        'grade_letter' => $fg->grade_letter,
                        'description' => $fg->description,
                    ]
                );
            }

            return $reportCard->fresh(['details.subject', 'student', 'semester']);
        });
    }

    public function generateForClass(int $classId, int $semesterId): int
    {
        $students = Student::query()->where('class_id', $classId)->pluck('id');
        $count = 0;

        foreach ($students as $studentId) {
            $this->generate($studentId, $semesterId, $classId);
            $count++;
        }

        return $count;
    }

    public function exportPdf(int $id): Response
    {
        $reportCard = $this->findOrFail($id);
        $pdf = Pdf::loadView('report-cards.pdf', compact('reportCard'));

        return $pdf->download("rapor-{$reportCard->student->nis}.pdf");
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
