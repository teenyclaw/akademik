<?php

namespace App\Http\Requests\AssessmentComponent;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentComponentRequest extends FormRequest
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
  'weight' => 'required|numeric|min:0|max:100',
  'sort_order' => 'nullable|integer|min:0',
;
    }
}
