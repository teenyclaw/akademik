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
