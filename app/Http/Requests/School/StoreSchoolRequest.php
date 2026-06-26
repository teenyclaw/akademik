<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'report_header' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }
}