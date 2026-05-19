<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\TimelineEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $projectIds = Project::where('user_id', $userId)->pluck('id');

        return response()->json([
            'success' => true,
            'data' => [
                'active_projects' => Project::where('user_id', $userId)
                    ->where('status', 'active')->count(),

                'total_earned' => Milestone::whereIn('project_id', $projectIds)
                    ->where('status', 'paid')->sum('amount'),

                'pending_payments' => Milestone::whereIn('project_id', $projectIds)
                    ->whereIn('status', ['delivered', 'approved'])->sum('amount'),

                'overdue_milestones' => Milestone::whereIn('project_id', $projectIds)
                    ->where('due_date', '<', now())
                    ->whereNotIn('status', ['paid'])->count(),

                'upcoming_deadlines' => Milestone::whereIn('project_id', $projectIds)
                    ->whereBetween('due_date', [now(), now()->addDays(7)])
                    ->whereNotIn('status', ['paid'])->count(),

                'recent_activity' => TimelineEvent::whereIn('project_id', $projectIds)
                    ->with('user:id,name')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(fn($e) => [
                        'id' => $e->id,
                        'user_name' => $e->user?->name,
                        'event_type' => $e->event_type,
                        'title' => $e->title,
                        'created_at' => $e->created_at,
                    ]),
            ],
        ]);
    }
}
