<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolClass extends Model
{
    use BelongsToSchool, HasAudit, HasFactory, SoftDeletes;

    protected $table = 'school_classes';

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'class_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'class_id');
    }

    public function reportCards(): HasMany
    {
        return $this->hasMany(ReportCard::class, 'class_id');
    }

    public function learningMaterials(): HasMany
    {
        return $this->hasMany(LearningMaterial::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }
}
