<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    public function subjectGroup(): BelongsTo
    {
        return $this->belongsTo(SubjectGroup::class);
    }

    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function curriculumSubjects(): HasMany
    {
        return $this->hasMany(CurriculumSubject::class);
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function finalGrades(): HasMany
    {
        return $this->hasMany(FinalGrade::class);
    }

    public function reportCardDetails(): HasMany
    {
        return $this->hasMany(ReportCardDetail::class);
    }

    public function learningMaterials(): HasMany
    {
        return $this->hasMany(LearningMaterial::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
