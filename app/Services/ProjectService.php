<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Payment;
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

    public function generateShareToken(Project $project): string
    {
        $token = Str::uuid()->toString();
        $project->update(['share_token' => $token]);

        return $token;
    }

    public function revokeShareToken(Project $project): void
    {
        $project->update(['share_token' => null]);
    }

    public function findByShareToken(string $token): ?Project
    {
        return Project::where('share_token', $token)->first();
    }

    public function getShareData(Project $project)
    {
        $milestones = $project->milestones()
            ->with('requirements')
            ->orderBy('order_index')
            ->get();

        $timeline = $project->timelineEvents()
            ->with('user:id,name')
            ->latest()
            ->limit(10)
            ->get();

        $payments = Payment::whereIn('milestone_id', $milestones->pluck('id'))
            ->orderBy('paid_at', 'desc')
            ->get();

        return [
            'project' => $project->load('user'),
            'milestones' => $milestones,
            'timeline' => $timeline,
            'payments' => $payments,
        ];
    }
}
