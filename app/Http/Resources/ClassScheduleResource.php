<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'class' => $this->whenLoaded('schoolClass', fn () => ['id' => $this->schoolClass->id, 'name' => $this->schoolClass->name]),
            'subject' => $this->whenLoaded('subject', fn () => ['id' => $this->subject->id, 'name' => $this->subject->name]),
            'teacher' => $this->whenLoaded('teacher', fn () => ['id' => $this->teacher->id, 'name' => $this->teacher->name]),
            'room' => $this->whenLoaded('room', fn () => $this->room ? ['id' => $this->room->id, 'name' => $this->room->name] : null),
            'lesson_hour' => $this->whenLoaded('lessonHour'),
        ];
    }
}
