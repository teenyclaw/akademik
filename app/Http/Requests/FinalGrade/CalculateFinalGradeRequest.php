<?php

namespace App\Http\Requests\FinalGrade;

use Illuminate\Foundation\Http\FormRequest;

class CalculateFinalGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => 'required|exists:school_classes,id',
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
        ];
    }
}
