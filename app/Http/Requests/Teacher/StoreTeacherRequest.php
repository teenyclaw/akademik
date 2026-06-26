<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nip' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:20',
            'joined_at' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
