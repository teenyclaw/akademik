<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'notes' => $this->notes,
            'student' => new StudentResource($this->whenLoaded('student')),
            'assessment' => $this->whenLoaded('assessment'),
        ];
    }
}
