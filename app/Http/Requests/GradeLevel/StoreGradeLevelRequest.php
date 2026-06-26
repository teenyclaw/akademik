<?php

namespace App\Http\Requests\GradeLevel;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
  'name' => 'required|string|max:255',
  'level_number' => 'required|integer|min:1',
  'description' => 'nullable|string',
;
    }
}
