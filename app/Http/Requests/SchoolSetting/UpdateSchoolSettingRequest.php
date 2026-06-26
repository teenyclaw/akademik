<?php

namespace App\Http\Requests\SchoolSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_name' => 'nullable|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:50',
            'school_email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'report_footer' => 'nullable|string',
        ];
    }
}
