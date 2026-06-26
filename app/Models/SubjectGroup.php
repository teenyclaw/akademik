<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubjectGroup extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
