<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AcademicYearController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\AnnouncementController;
use App\Http\Controllers\Web\AssessmentComponentController;
use App\Http\Controllers\Web\AssessmentController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\AssignmentSubmissionController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\ClassScheduleController;
use App\Http\Controllers\Web\FeeTypeController;
use App\Http\Controllers\Web\FinalGradeController;
use App\Http\Controllers\Web\GradeController;
use App\Http\Controllers\Web\GradeLevelController;
use App\Http\Controllers\Web\GuardianController;
use App\Http\Controllers\Web\LearningMaterialController;
use App\Http\Controllers\Web\LessonHourController;
use App\Http\Controllers\Web\MajorController;
use App\Http\Controllers\Web\PaymentBillController;
use App\Http\Controllers\Web\PaymentRecordController;
use App\Http\Controllers\Web\ReportCardController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\SchoolClassController;
use App\Http\Controllers\Web\SchoolController;
use App\Http\Controllers\Web\SchoolSettingController;
use App\Http\Controllers\Web\SemesterController;
use App\Http\Controllers\Web\StudentController;
use App\Http\Controllers\Web\SubjectController;
use App\Http\Controllers\Web\SubjectGroupController;
use App\Http\Controllers\Web\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'school.access'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'school.access'])->group(function () {
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('schools', SchoolController::class)->except(['show'])->middleware('permission:schools.view');
    });

    Route::resource('academic-years', AcademicYearController::class)->except(['show'])->middleware('permission:academic-years.view');
    Route::post('academic-years/{id}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate')->middleware('permission:academic-years.edit');

    Route::resource('semesters', SemesterController::class)->except(['show'])->middleware('permission:academic-years.view');
    Route::post('semesters/{id}/activate', [SemesterController::class, 'activate'])->name('semesters.activate')->middleware('permission:academic-years.edit');

    Route::resource('grade-levels', GradeLevelController::class)->except(['show'])->middleware('permission:grade-levels.view');
    Route::resource('majors', MajorController::class)->except(['show'])->middleware('permission:majors.view');
    Route::resource('classes', SchoolClassController::class)->except(['show'])->middleware('permission:classes.view');
    Route::resource('rooms', RoomController::class)->except(['show'])->middleware('permission:rooms.view');
    Route::resource('lesson-hours', LessonHourController::class)->except(['show'])->middleware('permission:lesson-hours.view');
    Route::resource('subject-groups', SubjectGroupController::class)->except(['show'])->middleware('permission:subjects.view');
    Route::resource('subjects', SubjectController::class)->except(['show'])->middleware('permission:subjects.view');
    Route::resource('teachers', TeacherController::class)->except(['show'])->middleware('permission:teachers.view');
    Route::resource('students', StudentController::class)->except(['show'])->middleware('permission:students.view');
    Route::resource('guardians', GuardianController::class)->except(['show'])->middleware('permission:parents.view');
    Route::resource('class-schedules', ClassScheduleController::class)->except(['show'])->middleware('permission:schedules.view');

    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index')->middleware('permission:attendances.view');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store')->middleware('permission:attendances.create');

    Route::resource('assessment-components', AssessmentComponentController::class)->except(['show'])->middleware('permission:grades.view');
    Route::resource('assessments', AssessmentController::class)->except(['show'])->middleware('permission:grades.view');

    Route::get('grades', [GradeController::class, 'index'])->name('grades.index')->middleware('permission:grades.view');
    Route::post('grades', [GradeController::class, 'store'])->name('grades.store')->middleware('permission:grades.create');

    Route::get('final-grades', [FinalGradeController::class, 'index'])->name('final-grades.index')->middleware('permission:grades.view');
    Route::post('final-grades/calculate', [FinalGradeController::class, 'calculate'])->name('final-grades.calculate')->middleware('permission:grades.edit');

    Route::get('report-cards', [ReportCardController::class, 'index'])->name('report-cards.index')->middleware('permission:report-cards.view');
    Route::post('report-cards/generate', [ReportCardController::class, 'generate'])->name('report-cards.generate')->middleware('permission:report-cards.create');
    Route::get('report-cards/{id}', [ReportCardController::class, 'show'])->name('report-cards.show')->middleware('permission:report-cards.view');
    Route::get('report-cards/{id}/pdf', [ReportCardController::class, 'pdf'])->name('report-cards.pdf')->middleware('permission:report-cards.view');
    Route::delete('report-cards/{id}', [ReportCardController::class, 'destroy'])->name('report-cards.destroy')->middleware('permission:report-cards.edit');

    Route::resource('announcements', AnnouncementController::class)->except(['show'])->middleware('permission:announcements.view');
    Route::resource('fee-types', FeeTypeController::class)->except(['show'])->middleware('permission:payments.view');
    Route::resource('payment-bills', PaymentBillController::class)->except(['show'])->middleware('permission:payments.view');
    Route::resource('payment-records', PaymentRecordController::class)->except(['show'])->middleware('permission:payments.create');
    Route::resource('learning-materials', LearningMaterialController::class)->except(['show'])->middleware('permission:elearning.view');
    Route::resource('assignments', AssignmentController::class)->except(['show'])->middleware('permission:elearning.view');
    Route::resource('assignment-submissions', AssignmentSubmissionController::class)->except(['show'])->middleware('permission:elearning.view');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:reports.view');
    Route::get('reports/export/students', [ReportController::class, 'exportStudents'])->name('reports.export.students')->middleware('permission:reports.export');
    Route::get('reports/export/teachers', [ReportController::class, 'exportTeachers'])->name('reports.export.teachers')->middleware('permission:reports.export');
    Route::get('reports/export/attendance', [ReportController::class, 'exportAttendance'])->name('reports.export.attendance')->middleware('permission:reports.export');
    Route::get('reports/export/grades', [ReportController::class, 'exportGrades'])->name('reports.export.grades')->middleware('permission:reports.export');
    Route::get('reports/export/payments', [ReportController::class, 'exportPayments'])->name('reports.export.payments')->middleware('permission:reports.export');

    Route::get('school-settings', [SchoolSettingController::class, 'edit'])->name('school-settings.edit')->middleware('permission:school-settings.view');
    Route::put('school-settings', [SchoolSettingController::class, 'update'])->name('school-settings.update')->middleware('permission:school-settings.edit');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show'])->middleware('permission:users.view');
    });
});

require __DIR__.'/auth.php';
