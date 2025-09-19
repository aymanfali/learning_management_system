<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignment_file' => $this->assignment_file
                ? asset('storage/' . $this->assignment_file)
                : null,

            // student info
            'student' => [
                'id' => $this->student?->id,
                'name' => $this->student?->name,
                'email' => $this->student?->email,
            ],

            // course info (via lesson)
            'course' => [
                'id' => $this->lesson?->course?->id,
                'name' => $this->lesson?->course?->name,
            ],

            // lesson info
            'lesson' => [
                'id' => $this->lesson?->id,
                'title' => $this->lesson?->title,
            ],

            // grading info
            'grade' => $this->grade,
            'feedback' => $this->feedback,
            'graded_at' => $this->graded_at,

            // timestamps
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
