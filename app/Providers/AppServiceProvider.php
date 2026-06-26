<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $bindings = [
            \App\Repositories\Contracts\SchoolRepositoryInterface::class => \App\Repositories\Eloquent\SchoolRepository::class,
            \App\Repositories\Contracts\AcademicYearRepositoryInterface::class => \App\Repositories\Eloquent\AcademicYearRepository::class,
            \App\Repositories\Contracts\SemesterRepositoryInterface::class => \App\Repositories\Eloquent\SemesterRepository::class,
            \App\Repositories\Contracts\GradeLevelRepositoryInterface::class => \App\Repositories\Eloquent\GradeLevelRepository::class,
            \App\Repositories\Contracts\MajorRepositoryInterface::class => \App\Repositories\Eloquent\MajorRepository::class,
            \App\Repositories\Contracts\SchoolClassRepositoryInterface::class => \App\Repositories\Eloquent\SchoolClassRepository::class,
            \App\Repositories\Contracts\RoomRepositoryInterface::class => \App\Repositories\Eloquent\RoomRepository::class,
            \App\Repositories\Contracts\LessonHourRepositoryInterface::class => \App\Repositories\Eloquent\LessonHourRepository::class,
            \App\Repositories\Contracts\SubjectGroupRepositoryInterface::class => \App\Repositories\Eloquent\SubjectGroupRepository::class,
            \App\Repositories\Contracts\SubjectRepositoryInterface::class => \App\Repositories\Eloquent\SubjectRepository::class,
            \App\Repositories\Contracts\TeacherRepositoryInterface::class => \App\Repositories\Eloquent\TeacherRepository::class,
            \App\Repositories\Contracts\StudentRepositoryInterface::class => \App\Repositories\Eloquent\StudentRepository::class,
            \App\Repositories\Contracts\GuardianRepositoryInterface::class => \App\Repositories\Eloquent\GuardianRepository::class,
            \App\Repositories\Contracts\ClassScheduleRepositoryInterface::class => \App\Repositories\Eloquent\ClassScheduleRepository::class,
            \App\Repositories\Contracts\AttendanceRepositoryInterface::class => \App\Repositories\Eloquent\AttendanceRepository::class,
            \App\Repositories\Contracts\AssessmentComponentRepositoryInterface::class => \App\Repositories\Eloquent\AssessmentComponentRepository::class,
            \App\Repositories\Contracts\AssessmentRepositoryInterface::class => \App\Repositories\Eloquent\AssessmentRepository::class,
            \App\Repositories\Contracts\GradeRepositoryInterface::class => \App\Repositories\Eloquent\GradeRepository::class,
            \App\Repositories\Contracts\FinalGradeRepositoryInterface::class => \App\Repositories\Eloquent\FinalGradeRepository::class,
            \App\Repositories\Contracts\ReportCardRepositoryInterface::class => \App\Repositories\Eloquent\ReportCardRepository::class,
            \App\Repositories\Contracts\AnnouncementRepositoryInterface::class => \App\Repositories\Eloquent\AnnouncementRepository::class,
            \App\Repositories\Contracts\FeeTypeRepositoryInterface::class => \App\Repositories\Eloquent\FeeTypeRepository::class,
            \App\Repositories\Contracts\PaymentBillRepositoryInterface::class => \App\Repositories\Eloquent\PaymentBillRepository::class,
            \App\Repositories\Contracts\PaymentRecordRepositoryInterface::class => \App\Repositories\Eloquent\PaymentRecordRepository::class,
            \App\Repositories\Contracts\LearningMaterialRepositoryInterface::class => \App\Repositories\Eloquent\LearningMaterialRepository::class,
            \App\Repositories\Contracts\AssignmentRepositoryInterface::class => \App\Repositories\Eloquent\AssignmentRepository::class,
            \App\Repositories\Contracts\AssignmentSubmissionRepositoryInterface::class => \App\Repositories\Eloquent\AssignmentSubmissionRepository::class,
            \App\Repositories\Contracts\SchoolSettingRepositoryInterface::class => \App\Repositories\Eloquent\SchoolSettingRepository::class,
            \App\Repositories\Contracts\UserRepositoryInterface::class => \App\Repositories\Eloquent\UserRepository::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\RecordLoginHistory::class,
        );
    }
}
