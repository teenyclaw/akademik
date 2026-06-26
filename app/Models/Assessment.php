<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
    use BelongsToSchool, HasAudit, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'max_score' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(AssessmentComponent::class, 'component_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
