<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Student;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(protected AttendanceRepositoryInterface $repository) {}

    public function getForClassAndDate(int $classId, string $date): Collection
    {
        $students = Student::query()->where('class_id', $classId)->orderBy('name')->get();
        $existing = Attendance::query()
            ->where('class_id', $classId)
            ->whereDate('date', $date)
            ->get()
            ->keyBy('student_id');

        return $students->map(fn ($student) => [
            'student' => $student,
            'attendance' => $existing->get($student->id),
        ]);
    }

    public function storeBulk(int $classId, string $date, array $records): void
    {
        DB::transaction(function () use ($classId, $date, $records) {
            foreach ($records as $studentId => $status) {
                Attendance::updateOrCreate(
                    ['student_id' => $studentId, 'date' => $date],
                    [
                        'class_id' => $classId,
                        'status' => $status,
                        'recorded_by' => auth()->id(),
                    ]
                );
            }
        });
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }
}
