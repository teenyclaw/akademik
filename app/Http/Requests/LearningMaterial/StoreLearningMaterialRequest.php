<?php

namespace App\Http\Requests\LearningMaterial;

use Illuminate\Foundation\Http\FormRequest;

class StoreLearningMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'file' => 'nullable|file|max:10240',
        ];
    }
}
