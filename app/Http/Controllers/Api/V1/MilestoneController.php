<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Project;
use App\Services\MilestoneService;
use App\Http\Resources\MilestoneResource;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    protected MilestoneService $milestoneService;

    public function __construct(MilestoneService $milestoneService)
    {
        $this->milestoneService = $milestoneService;
        $this->middleware('auth:api');
    }

    public function index(Project $project)
    {

        $this->authorize('view', $project);
        $milestones = $this->milestoneService->listByProject($project);

        return response()->json(
            [
                'success' => true,
                'data' => MilestoneResource::collection($milestones),
            ]
        );
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($project, $request) {
                    $milestoneId = $request->route('milestone'); // null for new, id for update
                    $otherMilestonesTotal = $project->milestones()
                        ->when($milestoneId, fn($q) => $q->where('id', '!=', $milestoneId))
                        ->sum('amount');

                    $newTotal = $otherMilestonesTotal + $value;
                    if ($newTotal > $project->total_amount) {
                        $fail("Total milestones (₹{$newTotal}) exceeds project budget of ₹{$project->total_amount}.");
                    }
                },
            ],
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,delivered,approved,paid',
            'order_index' => 'nullable|integer|min:0',
        ]);

        $milestones = $this->milestoneService->create(
            $project,
            $request->only(['title', 'description', 'amount', 'due_date', 'status', 'order_index'])
        );

        return response()->json([
            'success' => true,
            'message' => 'Milestone created',
            'data' => new MilestoneResource($milestones)
        ], 201);
    }

    public function show(Project $project, Milestone $milestone)
    {
        $this->authorize('view', $project);
        return response()->json([
            'success' => true,
            'data' => new MilestoneResource($milestone)
        ]);
    }

    public function update(Request $request, Project $project, Milestone $milestone)
    {
        $this->authorize('update', $project);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'amount' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($project, $request) {
                    $milestoneId = $request->route('milestone'); // null for new, id for update
                    $otherMilestonesTotal = $project->milestones()
                        ->when($milestoneId, fn($q) => $q->where('id', '!=', $milestoneId))
                        ->sum('amount');

                    $newTotal = $otherMilestonesTotal + $value;
                    if ($newTotal > $project->total_amount) {
                        $fail("Total milestones (₹{$newTotal}) exceeds project budget of ₹{$project->total_amount}.");
                    }
                },
            ],
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,delivered,approved,paid',
            'order_index' => 'nullable|integer|min:0'
        ]);

        $milestone = $this->milestoneService->update($milestone, $request->only([
            'title',
            'description',
            'amount',
            'due_date',
            'status',
            'order_index'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Milestone Updated',
            'data' => new MilestoneResource($milestone)
        ]);
    }

    public function destroy(Project $project, Milestone $milestone)
    {
        $this->authorize('delete', $project);

        $this->milestoneService->delete($milestone);

        return response()->json([
            'success' => true,
            'message' => 'Milestone deleted'
        ]);
    }


    public function updateStatus(Request $request, Project $project, Milestone $milestone)
    {
        $this->authorize('update', $project);

        $request->validate([
            'status' => 'required|in:pending,in_progress,delivered,approved,paid'
        ]);

        $milestone = $this->milestoneService->updateStatus($milestone, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Milestone Status Updated',
            'data' => new MilestoneResource($milestone)
        ]);
    }
}
