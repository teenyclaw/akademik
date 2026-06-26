<?php

namespace App\Http\Requests\Guardian;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuardianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ];
    }
}
