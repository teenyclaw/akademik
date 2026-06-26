<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentEducationHistory extends Model
{
    use BelongsToSchool, HasFactory;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
