<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Requirement;

class RequirementService
{
    public function listByProject(Project $project)
    {
        return $project->requirements()->latest()->get();
    }

    public function create(Project $project, array $data)
    {
        $requirement = $project->requirements()->create($data);

        app(TimelineService::class)->log(
            $project,
            'requirement_added',
            "Requirement added from {$requirement->source}"
        );

        return $requirement;
    }

    public function update(Requirement $requirement, array $data)
    {
        $requirement->update($data);
        app(TimelineService::class)->log(
            $requirement->project,
            'requirement_updated',
            "Requirement updated"
        );
        return $requirement;
    }

    public function delete(Requirement $requirement)
    {
        app(TimelineService::class)->log(
            $requirement->project,
            'requirement_deleted',
            "Requirement deleted"
        );
        return $requirement->delete();
    }
}
