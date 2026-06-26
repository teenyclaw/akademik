<?php

namespace App\Models;

use App\Domain\Enums\StudentStatus;
use App\Traits\BelongsToSchool;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Student extends Model
{
    use BelongsToSchool, HasAudit, HasFactory, LogsActivity, SoftDeletes;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'enrolled_at' => 'date',
            'status' => StudentStatus::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function biodata(): HasOne
    {
        return $this->hasOne(StudentBiodata::class);
    }

    public function educationHistories(): HasMany
    {
        return $this->hasMany(StudentEducationHistory::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardians')
            ->withPivot('relationship', 'is_primary')
            ->withTimestamps();
    }

    public function finalGrades(): HasMany
    {
        return $this->hasMany(FinalGrade::class);
    }

    public function reportCards(): HasMany
    {
        return $this->hasMany(ReportCard::class);
    }

    public function paymentBills(): HasMany
    {
        return $this->hasMany(PaymentBill::class);
    }

    public function assignmentSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
