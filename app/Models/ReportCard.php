<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportCard extends Model
{
    use BelongsToSchool, HasAudit, HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'student_id',
        'semester_id',
        'class_id',
        'homeroom_notes',
        'status',
        'generated_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReportCardDetail::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(ReportCardSignature::class);
    }
}
