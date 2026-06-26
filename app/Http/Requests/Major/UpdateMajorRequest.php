<?php

namespace App\Http\Requests\Major;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMajorRequest extends FormRequest
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
  'description' => 'nullable|string',
;
    }
}
