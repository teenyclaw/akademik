<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => 'nullable|exists:school_classes,id',
            'nis' => 'required|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:20',
            'enrolled_at' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'biodata.blood_type' => 'nullable|string|max:5',
            'biodata.height' => 'nullable|integer|min:0',
            'biodata.weight' => 'nullable|integer|min:0',
            'biodata.disabilities' => 'nullable|string',
            'biodata.notes' => 'nullable|string',
        ];
    }
}
