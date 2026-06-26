<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EducationLevel extends Model
{
    use HasFactory;

    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }
}
