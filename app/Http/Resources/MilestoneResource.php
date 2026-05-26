<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilestoneResource extends JsonResource
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
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'order_index' => $this->order_index,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'requirements' => RequirementResource::collection($this->whenLoaded('requirements')),
        ];
    }
}
