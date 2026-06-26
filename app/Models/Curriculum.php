<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curriculum extends Model
{
    use BelongsToSchool, HasAudit, HasFactory, SoftDeletes;

    protected $table = 'curricula';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function curriculumSubjects(): HasMany
    {
        return $this->hasMany(CurriculumSubject::class);
    }
}
