<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentComponent extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
        ];
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'component_id');
    }
}
