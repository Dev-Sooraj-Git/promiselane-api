<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $nextMilestone = $this->milestones()
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->orderBy('due_date')
            ->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'created_at' => $this->created_at,
            'milestones_total' => $this->milestones()->count(),
            'milestones_completed' => $this->milestones()->whereIn('status', ['approved', 'paid'])->count(),
            'next_milestone' => $nextMilestone?->title,
            'next_due_date' => $nextMilestone?->due_date,
            'user' => $this->whenLoaded('user', fn() => [
                'name' => $this->user->name,
            ]),
        ];
    }
}
