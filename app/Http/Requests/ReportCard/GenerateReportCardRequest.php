<?php

namespace App\Http\Requests\ReportCard;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportCardRequest extends FormRequest
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
            'student_id' => 'nullable|exists:students,id',
        ];
    }
}
