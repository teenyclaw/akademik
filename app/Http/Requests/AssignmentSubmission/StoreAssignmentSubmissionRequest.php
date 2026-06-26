<?php

namespace App\Http\Requests\AssignmentSubmission;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignment_id' => 'required|exists:assignments,id',
            'student_id' => 'required|exists:students,id',
            'file' => 'nullable|file|max:10240',
            'status' => 'nullable|string|max:20',
        ];
    }
}
