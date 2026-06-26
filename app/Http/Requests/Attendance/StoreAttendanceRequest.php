<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => 'required|exists:school_classes,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:H,S,I,A',
        ];
    }
}
