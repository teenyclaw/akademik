<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonHour extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
