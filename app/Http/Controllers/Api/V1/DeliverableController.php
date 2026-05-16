<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliverableResource;
use App\Models\Deliverable;
use App\Models\Milestone;
use App\Models\Project;
use App\Services\DeliverableService;
use Illuminate\Http\Request;

class DeliverableController extends Controller
{

    protected DeliverableService $deliverableService;

    public function __construct(DeliverableService $deliverableService)
    {
        $this->deliverableService = $deliverableService;
        $this->middleware('auth:api');
    }

    public function index(Project $project, Milestone $milestone)
    {
        $this->authorize('view', $project);

        $deliverables = $this->deliverableService->listByMilestone($milestone);
        return response()->json([
            'success' => true,
            'data' => DeliverableResource::collection($deliverables)
        ]);
    }

    public function store(Request $request, Project $project, Milestone $milestone)
    {
        $this->authorize('update', $project);

        $request->validate([
            'file_name' => 'required|string',
            'path' => 'required|string',
            'mime_type' => 'required|string',
            'size' => 'required|integer',
            'notes' => 'nullable|string'
        ]);

        $deliverable = $this->deliverableService->create($milestone, $request->only([
            'file_name',
            'path',
            'mime_type',
            'size',
            'notes'
        ]));

        return response()->json([
            "success" => true,
            "message" => 'Deliverables Created',
            "data" => new DeliverableResource($deliverable)
        ], 201);
    }

    public function destroy(Project $project ,Milestone $milestone, Deliverable $deliverable)
    {
        $this->authorize('delete', $project);
        $this->deliverableService->delete($deliverable);
        return response()->json([
            "success" => true,
            "message" => "Deliverable Deleted"
        ]);
    }
}
