<?php

namespace App\Services;

use App\Models\Project;
use App\Models\TimelineEvent;
use Illuminate\Support\Facades\Auth;

class TimelineService
{
    public function log(Project $project, string $eventType, string $title, ?string $description = null, ?array $metadata = null): TimelineEvent
    {
        return TimelineEvent::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'event_type' => $eventType,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    public function listByProject(Project $project)
    {
        return TimelineEvent::where('project_id', $project->id)
            ->with('user:id,name')
            ->latest()
            ->get();
    }
}
