<?php

namespace App\Traits;

use App\Models\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSchool
{
    public static function bootBelongsToSchool(): void
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
                $schoolId = session('school_id') ?? auth()->user()->school_id;
                if ($schoolId) {
                    $builder->where($builder->getModel()->getTable().'.school_id', $schoolId);
                }
            }
        });

        static::creating(function ($model) {
            if (! $model->school_id && auth()->check()) {
                $model->school_id = session('school_id') ?? auth()->user()->school_id;
            }
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
