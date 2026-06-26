<?php

namespace App\Http\Requests\FeeType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
  'name' => 'required|string|max:255',
  'amount' => 'required|numeric|min:0',
  'description' => 'nullable|string',
;
    }
}
