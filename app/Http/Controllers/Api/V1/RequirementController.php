<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequirementResource;
use App\Models\Project;
use App\Services\RequirementService;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    //
    protected RequirementService $RequirementService;

    public function __construct(RequirementService $RequirementService)
    {
        $this->RequirementService = $RequirementService;
        $this->middleware('auth:api');
    }

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $requirements = $this->RequirementService->listByProject($project);

        return response()->json([
            "success" => true,
            "data" => RequirementResource::collection($requirements)
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize("update", $project);

        $request->validate([
            'content' => 'required|string',
            'source' => 'required|in:chat,email,call,document,other',
            'status' => 'nullable|in:requested,agreed,rejected,pending_clarification',
            'is_in_scope' => 'nullable|boolean',
            'clarification_notes' => 'nullable|string',
            'attachments' => 'nullable|json '
        ]);


        $requirement = $this->RequirementService->create($project, $request->only([
            'content',
            'source',
            'status',
            'is_in_scope',
            'clarification_notes',
            'attachments'
        ]));

        return response()->json([
            "success" => true,
            "message" => 'Requirement Created',
            "data" => new RequirementResource($requirement)
        ], 201);
    }
}
