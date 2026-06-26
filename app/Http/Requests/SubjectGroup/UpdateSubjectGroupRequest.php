<?php

namespace App\Http\Requests\SubjectGroup;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
  'name' => 'required|string|max:255',
  'code' => 'required|string|max:50',
;
    }
}
