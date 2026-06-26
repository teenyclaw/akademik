<?php

namespace App\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assessment_id' => 'required|exists:assessments,id',
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:999',
        ];
    }
}
