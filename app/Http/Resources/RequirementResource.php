<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "project_id" => $this->project_id,
            "milestone_id" => $this->milestone_id,
            "source" => $this->source,
            "content" => $this->content,
            "status" => $this->status,
            "is_in_scope" => $this->is_in_scope,
            "clarification_notes" => $this->clarification_notes,
            "attachments" => $this->attachments,
            "created_at" => $this->created_at
        ];
    }
}
