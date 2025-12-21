<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        unset($request);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'user' => $this->whenLoaded('user') ? (new UserResource($this->user))->toArray(request()) : null,
            'comments' => $this->whenLoaded('comments')
                ? $this->comments->map(fn ($comment) => (new CommentResource($comment))->toArray(request()))
                : [],
            'due_date' => $this->due_date?->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),   ];
    }
}
