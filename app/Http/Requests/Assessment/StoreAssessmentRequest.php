<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:school_classes,id',
            'component_id' => 'required|exists:assessment_components,id',
            'name' => 'required|string|max:255',
            'max_score' => 'required|numeric|min:1|max:999',
            'date' => 'nullable|date',
        ];
    }
}
