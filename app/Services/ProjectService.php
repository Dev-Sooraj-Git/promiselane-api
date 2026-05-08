<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


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
        $data['slug'] = Str::slug($data['title'] . '-' . Str::random(6));
        return Project::create($data);
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
        return $project;
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
