<?php

namespace App\Http\Requests\Semester;

use Illuminate\Foundation\Http\FormRequest;

class StoreSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
  'academic_year_id' => 'required|exists:academic_years,id',
  'name' => 'required|string|max:255',
  'semester_number' => 'required|integer|in:1,2',
  'start_date' => 'required|date',
  'end_date' => 'required|date|after:start_date',
;
    }
}
