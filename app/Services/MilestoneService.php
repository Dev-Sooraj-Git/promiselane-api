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
        return $project->milestones()->create($data);
    }

    public function update(Milestone $milestone, array $data): Milestone
    {
        $milestone->update($data);
        return $milestone;
    }

    public function delete(Milestone $milestone): void
    {
        $milestone->delete();
    }

    public function updateStatus(Milestone $milestone, string $status): Milestone
    {
        $data = ['status' => $status];

        if ($status === 'paid') {
            $data['paid_at'] = now();
        }

        $milestone->update($data);
        return $milestone;
    }
}
