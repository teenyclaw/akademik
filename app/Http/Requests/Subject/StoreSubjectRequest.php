<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
  'subject_group_id' => 'nullable|exists:subject_groups,id',
  'code' => 'required|string|max:50',
  'name' => 'required|string|max:255',
  'description' => 'nullable|string',
;
    }
}
