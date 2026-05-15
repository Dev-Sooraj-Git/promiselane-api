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
        return $project->requirements()->create($data);
    }

    public function update(Requirement $requirement, array $data)
    {
        $requirement->update($data);
        return $requirement;
    }

    public function delete(Requirement $requirement)
    {
        return $requirement->delete();
    }
}
