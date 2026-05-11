<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Milestone;

class MilestoneService
{
    public function listByProject(int $project_id)
    {
        return Milestone::Where('project_id', $project_id)
            ->OrderBy('order_index', 'asc')
            ->get();
    }

    public function create(Project $project, array $data)
    {
        return $project->milestone()::create($data);
    }
}
