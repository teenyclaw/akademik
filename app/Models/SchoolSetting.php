<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolSetting extends Model
{
    use BelongsToSchool, HasFactory;

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }
}
