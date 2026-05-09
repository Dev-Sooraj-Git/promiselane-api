<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->middleware('auth:api');
    }

    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->list(
            Auth::id(),
            $request->query('status')
        );

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'total_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,completed,cancelled',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ]);

        $project = $this->projectService->create($request->only([
            'title',
            'description',
            'client_name',
            'client_email',
            'total_amount',
            'status',
            'started_at',
            'completed_at',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Project created.',
            'data' => new ProjectResource($project),
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($this->projectService->show($project)),
        ]);
    }
}
