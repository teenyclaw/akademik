<?php

namespace App\Http\Requests\Announcement;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
  'title' => 'required|string|max:255',
  'content' => 'required|string',
  'type' => 'nullable|string|max:50',
  'published_at' => 'nullable|date',
  'expires_at' => 'nullable|date|after:published_at',
;
    }
}
