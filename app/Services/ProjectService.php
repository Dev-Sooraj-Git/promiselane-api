<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\TimelineService;


class ProjectService
{
    /**
     * Create a new class instance.
     */
    public function List(int $userId, string $status = null)
    {
        return Project::Where('user_id', $userId)
            ->When($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->get();
    }

    public function create(array $data)
    {

        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        $project = Project::create($data);

        app(TimelineService::class)->log(
            $project,
            'project_created',
            "Project '{$project->title}' created"
        );

        return $project;
    }

    public function show(Project $project)
    {
        return $project;
    }

    public function update(Project $project, array $data)
    {
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title'] . '-' . Str::random(6));
        }

        $project->update($data);

        app(TimelineService::class)->log(
            $project,
            'project_updated',
            "Project '{$project->title}' updated"
        );

        return $project;
    }

    public function delete(Project $project): void
    {
        app(TimelineService::class)->log(
            $project,
            'project_deleted',
            "Project '{$project->title}' deleted"
        );

        $project->delete();
    }
}
