<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Milestone;

class MilestoneService
{
    // public function listByProject(int $project_id)
    // {
    //     return Milestone::Where('project_id', $project_id)
    //         ->OrderBy('order_index', 'asc')
    //         ->get();
    // } // added by Sooraj 12-05-2026

    public function listByProject(Project $project)
    {
        return $project->milestones()
            ->orderBy('order_index', 'asc')
            ->get();
    }

    public function create(Project $project, array $data)
    {
        $milestone = $project->milestones()->create($data);

        app(TimelineService::class)->log(
            $project,
            'milestone_created',
            "Milestone '{$milestone->title}' added"
        );

        return $milestone;
    }

    public function update(Milestone $milestone, array $data): Milestone
    {
        $milestone->update($data);
        app(TimelineService::class)->log(
            $milestone->project,
            'milestone_updated',
            "Milestone '{$milestone->title}' updated"
        );
        return $milestone;
    }

    public function delete(Milestone $milestone): void
    {
        app(TimelineService::class)->log(
            $milestone->project,
            'milestone_deleted',
            "Milestone '{$milestone->title}' deleted"
        );
        $milestone->delete();
    }

    public function updateStatus(Milestone $milestone, string $status): Milestone
    {
        $data = ['status' => $status];

        if ($status === 'paid') {
            $data['paid_at'] = now();
        }

        $milestone->update($data);
        app(TimelineService::class)->log(
            $milestone->project,
            'milestone_status_updated',
            "Milestone '{$milestone->title}' marked as '{$status}'"
        );
        return $milestone;
    }
}
