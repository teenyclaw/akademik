<?php

namespace App\Http\Requests\SchoolClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolClassRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'major_id' => 'nullable|exists:majors,id',
            'name' => 'required|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1',
        ];
    }
}